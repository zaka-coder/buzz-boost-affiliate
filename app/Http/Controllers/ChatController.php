<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index($role)
    {
        if($role == 'admin')
        {
            return abort(404);
        }

        $senderIds = Chat::where('sender_id', Auth::user()->id)
            ->where('parent_id', null)
            ->pluck('receiver_id');

        $receiverIds = Chat::where('receiver_id', Auth::user()->id)
            ->where('parent_id', null)
            ->pluck('sender_id');

        $receivers = $senderIds->merge($receiverIds)->unique();

        // get $receivers data from users table
        $receiversData = User::whereIn('id', $receivers)->get();

        $messages = [];
        $recepient = null;

        return view('messages.index', compact('messages', 'recepient', 'receiversData', 'role'));

    }

    public function show($role, $receiver_id)
    {
        // get all messages of the receiver
        $messages = Chat::where('parent_id', '!=', null)
            ->where(function ($query) use ($receiver_id) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($query) use ($receiver_id) {
                $query->where('receiver_id', Auth::user()->id)
                    ->where('sender_id', $receiver_id)
                    ->where('parent_id', '!=', null);
            })
            ->get();

        $recepient = User::find($receiver_id);

        // get all the receviers list of the sender
        $senderIds = Chat::where('sender_id', Auth::user()->id)
            ->where('parent_id', null)
            ->pluck('receiver_id');

        $receiverIds = Chat::where('receiver_id', Auth::user()->id)
            ->where('parent_id', null)
            ->pluck('sender_id');

        $receivers = $senderIds->merge($receiverIds)->unique();


        // get $receivers data from users table
        $receiversData = User::whereIn('id', $receivers)->get();

        return view('messages.index', compact('messages', 'recepient', 'receiversData', 'role'));

    }

    public function store(Request $request, $role)
    {
        $request->validate([
            'receiver_id' => 'required',
        ]);

        // check if current user is not the receiver
        if ($request->receiver_id == Auth::user()->id) {
            return redirect()->back()->with('error', 'You cannot send message to yourself.');
        }

        // check if user has already a message with the receiver
        $existingMessage = Chat::where('sender_id', Auth::user()->id)
            ->where('receiver_id', $request->receiver_id)
            ->where('parent_id', null)
            ->first();

        if ($existingMessage) {
            return redirect()->route('chats.show', ['role' => $role, 'receiver_id' => $request->receiver_id]);
        } else {
            $chat = Chat::create([
                'sender_id' => Auth::user()->id,
                'receiver_id' => $request->receiver_id,
            ]);

            Notification::create([
                'user_id' => $request->receiver_id,
                'title' => 'You have a new message from ' . Auth::user()->name,
                'is_read'=> 0,
            ]);

            return redirect()->route('chats.show', ['role' => $role, 'receiver_id' => $request->receiver_id]);
        }

        return redirect()->back()->with('error', 'Something went wrong! Please try again.');
    }

    public function reply(Request $request, $role, $receiver_id)
    {
        // dd($request->all());
        $request->validate([
            'message' => 'required',
        ]);

        // get parent message of the reply
        $message = Chat::where('parent_id', null)
            ->where(function ($query) use ($receiver_id) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($query) use ($receiver_id) {
                $query->where('receiver_id', Auth::user()->id)
                    ->where('sender_id', $receiver_id)
                    ->where('parent_id', null);
            })
            ->first();


        Chat::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $receiver_id,
            'message' => $request->message,
            'parent_id' => $message->id,
        ]);

        Notification::create([
            'user_id' => $receiver_id,
            'title' => 'You have a new message from ' . Auth::user()->name,
            'is_read'=> 0,
        ]);

        return back();
    }

    public function search(Request $request, $role)
    {
        // $request->validate([
        //     'search' => 'required',
        // ]);

        if (!$request->search || $request->search == '') {
            return response()->json([
                'search' => false,
                'users' => [],
                'role' => $role
            ]);
        }

        $searchTerm = $request->search;

        // get all the receviers list of the sender
        $senderIds = Chat::where('sender_id', Auth::user()->id)
            ->where('parent_id', null)
            ->pluck('receiver_id');

        $receiverIds = Chat::where('receiver_id', Auth::user()->id)
            ->where('parent_id', null)
            ->pluck('sender_id');

        $receivers = $senderIds->merge($receiverIds)->unique();


        // get $receivers data from users table
        $receiversData = User::whereIn('id', $receivers)->get();

        // get the searched users from the $receiversData
        $receiversData = $receiversData->filter(function ($user) use ($searchTerm) {
            return Str::contains(strtolower($user->name), strtolower($searchTerm));
        });

        foreach ($receiversData as $user) {
            // $user->role = $role;
            $user->name = strtolower(str_replace(' ', '', $user->name));
        }

        return response()->json([
            'search' => true,
            'users' => $receiversData,
            'role' => $role
        ]);


        // $messages = [];
        // $recepient = null;

        // return view('messages.index', compact('messages', 'recepient', 'receiversData', 'role'));
    }
}
