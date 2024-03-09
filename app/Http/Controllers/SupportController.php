<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index($role)
    {
        $role = strtolower($role);
        $user = auth()->user();

        $supports = Support::orderBy('id', 'desc')
            ->where('user_id', $user->id)
            ->where('user_role', $role)
            ->where('parent_id', null)
            ->get();

        if ($role == 'seller') {
            return view('seller.support.index', compact('supports', 'role'));
        } elseif ($role == 'buyer') {
            return view('buyer.support.index', compact('supports', 'role'));
        }
        return abort(404);
    }

    public function create($role)
    {
        if ($role == 'seller') {
            return view('seller.support.create', compact('role'));
        } elseif ($role == 'buyer') {
            return view('buyer.support.create', compact('role'));
        }
        return abort(404);
    }

    public function store(Request $request, $role)
    {
        // dd($request->all());
        if ($request->message == null || $request->message == '') {
            return back()->with('error', 'Question cannot be empty.');
        }

        Support::create([
            'user_id' => auth()->user()->id,
            'user_role' => $role,
            'message' => $request->message,
            'category' => $request->category,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Support ticket submitted successfully.');
    }
    
    public function storeReply(Request $request, $role, $parent_id)
    {
        // dd($request->all());
        if ($request->comment == null || $request->comment == '') {
            return back()->with('error', 'Message cannot be empty.');
        }

        $url = null;
        // check for attachment
        if ($request->hasFile('attachment')) {
            $request->validate([
                'attachment' => 'image|max:2048',
            ]);

            $attachment = $request->file('attachment');
            // $name_gen = uniqid(); // create unique id
            $img_ext = strtolower($attachment->getClientOriginalExtension());
            $filename =  $parent_id . '-' . time() . '.' . $img_ext;
            $folderPath = 'images/support_tickets/';
            $url = $folderPath .  $filename;
            $attachment->move($folderPath, $filename); // save image to public folder
        }

        Support::create([
            'user_id' => auth()->user()->id,
            'user_role' => $role,
            'message' => $request->comment,
            'status' => 'reply',
            'parent_id' => $parent_id,
            'attachment' => $url,
        ]);

        return back()->with('success', 'Reply submitted successfully.');
    }
}
