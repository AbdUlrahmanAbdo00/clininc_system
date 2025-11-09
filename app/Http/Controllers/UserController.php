<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\OtpTrait;
use Stichoza\GoogleTranslate\GoogleTranslate;

class UserController extends Controller
{
    use OtpTrait;


    public function Secretary_login(Request $request){
        $validator = Validator::make($request->all(), [
        'name'     => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 400);
    }

    
    if (
        $request->name === env('SECRETARY_NAME') &&
        $request->password === env('SECRETARY_PASSWORD')
    ) {

        $user = User::firstOrCreate(
            ['number' => '0000000000'], 
            ['first_name' =>"secretary" ]
        );

        Auth::login($user);


        $token = $user->createToken('clinic_sys')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'data' => [
                'token'       => $token,
                'token_type'  => 'bearer',
                'filled_data' => true,
            ]
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => 'Unauthorized'
    ], 400);

    }


    public function requestOtp(Request $request)
    {
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $request->validate([
            'number' => ['required', 'regex:/^(\+963|09)\d{8}$/'],
            'role' => 'string|max:255',
        ]);

        $role = $request->role;
        $check = true;
        $num = 400;
        $user = User::where('number', $request->number)->first();

        if ($user) {
            if ($role == "doctor" && !$user->hasRole('doctor')) {
                $check = false;
            }
        } else {
            if ($role === "doctor") {
                $check = false;
            }
        }

        if ($check) {
            $result = $this->sendOTP($request->number);
        }

        if (isset($result['success']) && $result['success'] == true) {
            $num = 200;
        }

        if (isset($result['data']) && is_array($result['data'])) {
            $result['data']['check'] = $check;
        }

        return response()->json([
            "success" => $result['success'] ?? false,
            "message" => $translator->translate($result['message'] ?? 'Change the role to Patient role'),
            "data" => $result['data'] ?? false,
        ], $num);
    }

public function verif(Request $request)
{
    $lan = $request->header('lan', 'en');
    $translator = new GoogleTranslate($lan);

    $validator = Validator::make($request->all(), [
        'number' => 'required',
        'otp'    => 'required',
        'fcm'    => 'nullable|string' 
    ]);

    // $result = $this->verifyOTP($request->number, $request->otp);
    
   
    // if (!$result['success']) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => $translator->translate($result['message'] ?? 'Unauthorized'),
    //     ], 400);
    // }

    $filled_data = true;
    $user = User::where('number', $request->number)->first();

    $messag = 'Logged in successfully.';

    if (!$user) {
        $messag = 'Account created successfully. Welcome!';
        $user = User::create([
            'number' => $request->number
        ]);
        $filled_data = false;
    }

    $requiredFields = [
        'first_name', 'middle_name', 'last_name', 'number',
        'mother_name', 'birth_day', 'national_number', 'gender'
    ];

    foreach ($requiredFields as $field) {
        if (empty($user->$field)) {
            $filled_data = false;
            break;
        }
    }

    $userOtp = Otp::where('phone', $request->number)->first();

    if (true) {
        Auth::login($user);

        $token = $user->createToken('clinic_sys')->plainTextToken;

        if (!empty($request->fcm)) {
            \App\Models\FcmToken::where('token', $request->fcm)->delete();

            $user->fcmTokens()->create([
                'token' => $request->fcm,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $translator->translate($messag),
            'data' => [
                'token'       => $token,
                'token_type'  => 'bearer',
                'filled_data' => $filled_data,
            ]
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $translator->translate('Unauthorized')
    ], 400);
}


    public function logout(Request $request)
    {
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => $translator->translate('Logged out successfully.')
        ], 200);
    }

    public function logoutFromAll(Request $request)
    {
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => $translator->translate('Logged out successfully.')
        ], 200);
    }
}
