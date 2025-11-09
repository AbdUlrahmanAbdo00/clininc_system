<?php

namespace App\Services;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

 class OtpService {
public function sendOtp( string $phone){
 $otpMsg = rand(1000,9999);
    Otp::updateOrInsert(
        ['phone'=>$phone, ],
        ['otp' => $otpMsg, 'created_at' => now(), 'updated_at' => now()]

    );
        Http::post('https://api.ultramsg.com/'. env('ULTRAMSG_INSTANCE') .'/messages/chat', [
        'token' => env('ULTRAMSG_TOKEN'),
        'to' => $phone,
        'body' => 'Your OTP is: ' . $otpMsg . '\nDO NOT share the code with anyone, The code will expire in 5 minutes.'
    ]);
    
    return [
        'phone' => $phone,
        'expires_in' => 300
    ];
}
    public function verifyOTP(string $phone ,string $otpMsg){
         $checkotp = Otp::where([
            'phone'=>$phone,
            'otp'=>"0000"
         
         ])->first();
         if(!$checkotp){
            return [
                'success' => false,
                'message' => 'Invalid OTP'
            ];
        }
        // dd($checkotp);
        // dd($checkotp->created_at);
        // dd(Carbon::parse($checkotp->created_at)->addMinutes(5)->isPast());
        if(Carbon::parse($checkotp->created_at)->addMinutes(5)->isPast()){
            return [
                'success' => false,
                'message' => 'OTP has expired.'
            ];
        }

        // Otp::where('phone', $phone)
        //     ->delete();

        return [
            'success' => true,
            'message' => 'OTP verified'
        ];
                      

    }


 }
