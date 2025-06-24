<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function sendTestNotification(FirebaseService $firebase, Request $request)
    {
       

        $request->validate([
            'deviceToken' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'required"array'
        ]);

        $firebase->sendNotification(
            $request->deviceToken,
            $request->title,
            $request->body
        );

        return response()->json([
            'message' => 'Notification sent'
        ]);
    }
}
