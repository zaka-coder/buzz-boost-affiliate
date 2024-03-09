<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $support_tickets = Support::where('parent_id', null)->get();
        return view('admin.support.index', compact('support_tickets'));
    }

    // public function store(Request $request, $role)
    // {
    //     // dd($request->all());
    //     if ($request->message == null || $request->message == '') {
    //         return back()->with('error', 'Question cannot be empty.');
    //     }

    //     Support::create([
    //         'user_id' => auth()->user()->id,
    //         'user_role' => $role,
    //         'message' => $request->message,
    //         'category' => $request->category,
    //         'status' => 'Pending',
    //     ]);

    //     return back()->with('success', 'Support ticket submitted successfully.');
    // }

    public function reply(Request $request, $parent_id)
    {
        // dd($request->all());
        if ($request->comment == null || $request->comment == '') {
            return back()->with('error', 'Message cannot be empty.');
        }

        // update status of parent
        if($request->status){
            Support::where('id', $parent_id)->update(['status' => $request->status]);
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
            'user_role' => 'admin',
            'message' => $request->comment,
            'status' => 'reply',
            'parent_id' => $parent_id,
            'attachment' => $url,
        ]);

        Notification::create([
            'user_id' => Support::where('id', $parent_id)->first()->user_id,
            'title' => 'You have a new reply on Support ticket.',
            'is_read'=> 0,
        ]);

        return back()->with('success', 'Reply submitted successfully.');
    }

    public function destroy($id)
    {
        dd($id);
        Support::where('id', $id)->delete();
        return back()->with('success', 'Support ticket deleted successfully.');
    }

    public function filterByDate(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $endDate = date('Y-m-d', strtotime($endDate . ' +1 day')); // add 1 day to endDate to include the end day orders

        $support_tickets = Support::whereBetween('created_at', [$startDate, $endDate])->where('parent_id', null)->get();
        // dd($support_tickets);

        return view('admin.support.index', compact('support_tickets'));
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->status;
        $support_tickets = Support::where('status', 'like', '%' . $status . '%')->where('parent_id', null)->get();
        return view('admin.support.index', compact('support_tickets', 'status'));
    }
}
