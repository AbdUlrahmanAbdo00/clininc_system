<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function sendTestNotification(FirebaseService $firebase, Request $request)
    {
        $raw = env('FIREBASE_CREDENTIALS');

// إزالة العلامات الزائدة إذا موجودة
if (Str::startsWith($raw, '"') && Str::endsWith($raw, '"')) {
    $raw = substr($raw, 1, -1); // إزالة أول وآخر " من النص
    $raw = str_replace('\"', '"', $raw); // إعادة الاقتباسات الداخلية لوضعها الصحيح
    $raw = str_replace('\\n', "\n", $raw); // تحويل \n لنهاية سطر حقيقية
}
$credentials = json_decode($raw, true);

dd($credentials);

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
