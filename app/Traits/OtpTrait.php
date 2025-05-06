<?php

namespace App\Traits;

use App\Models\Otp;
use App\Services\OtpService;
use Illuminate\Http\Request;

trait OtpTrait
{


    public function sendOTP($phone){
        $otpService = new OtpService();
        $result = $otpService->sendOTP($phone);
        return [
            'success' => true,
            'message' => 'OTP sent successfully.',
            'data' => $result
        ];
    }

    public function verifyOTP($phone, $otp){
        $otpService = new OtpService();
        return $otpService->verifyOTP($phone, $otp);
    }
}
