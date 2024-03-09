<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read(Request $request)
    {
        $isRead = Notification::where('user_id', $request->user()->id)->where('is_read', 0)->update([
            'is_read' => 1
        ]);

        
        if(!$isRead) {
            return response()->json(['success' => false]);
        } else {
            return response()->json(['success' => true]);
        }
    }
}
