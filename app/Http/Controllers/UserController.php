<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Otp;
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\OtpTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use OtpTrait;
    public function requestOtp(Request $request)
    {

        // $host = 'api.smsprovider.com'; // غيّرها لاسم المزود الحقيقي
        // $ip = gethostbyname($host);

        // echo "Resolved IP for $host: $ip\n";
        $request->validate([
            'number' => 'required',
            'role' => 'string|max:255',
        ]);
        $role = $request->role;
        $check = "true";
        $user = User::where('phone', $request->number)->first();
        if ($user::hasRole('doctor')) {

            if ($role != "doctor") {
                $check = "false";
            }
        }
        $result = $this->sendOTP($request->number);
        if ($result==true)
        {$num=200;}
        else
        $num=400;
        return response()->json([
            "result" => $result,
            "check" => $check
        ], $num);
    }
    public function verif(Request $request)
    {


        $validator = Validator::make($request->all(), [
            // 'name'=>'required|string|max:255',
            'number' => 'required',
            'otp' => 'required'

        ]);
        // dd($validator);

        $result = $this->verifyOTP($request->number, $request->otp);
        //  dd($result);
        //  dd($result['success']);
        if (!$result['success']) {
            return response()->json($result, 401);
        }
        $filled_data = true;

        $user = User::where('number', $request->number)->first();
          $messag="Logged in successfully.";
        // dd($user);
        if (!$user) {
            $messag= "Account created successfully. Welcome!";
            $user = User::create([
                'number' => $request->number
            ]);
            $user->assignRole('patient');
            $filled_data = false;
        }


        $userOtp = Otp::where('phone', $request->number)->first();
        if ($request->otp == $userOtp->otp) {
            Auth::login($user);

            $token = $user->createToken('clinic_sys')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $messag,
                'data' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'filled_data' => $filled_data,
                ]
            ], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }





    // public function selectMode(Request $request)
    // {
    //     $request->validate([
    //         'mode' => 'required|in:normal,emergency,follow-up',
    //     ]);

    //     $user = Auth::user();

    //     if ($user->role !== 'doctor') {
    //         return response()->json([
    //             'message' => 'Only doctors can select a mode.',
    //         ], 403);
    //     }

    //     // ممكن تحفظه بجلسة أو بقاعدة البيانات
    //     session(['doctor_mode' => $request->mode]);

    //     // أو لو عندك عمود مثلاً current_mode بجدول doctors
    //     // $user->doctor->update(['current_mode' => $request->mode]);

    //     return response()->json([
    //         'message' => 'Mode selected successfully',
    //         'mode' => $request->mode,
    //     ]);
    // }




    // // تسجيل الخروج
    // public function logout()
    // {
    //     Auth::logout();
    //     return response()->json([
    //         'message' => 'Logged out successfully',
    //     ]);
    // }
}
