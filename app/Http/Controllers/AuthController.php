<?php

namespace App\Http\Controllers;

use App\Models\AdminCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Doctors;
use App\Models\Patients;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * عرض صفحة إنشاء حساب جديد
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * تسجيل الدخول
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credential = AdminCredential::where('username', $request->username)->first();

        if ($credential && $request->password=== $credential->password) {
            // إنشاء توكن جديد
            $token = $credential->user->createToken('admin_token')->plainTextToken;
            
            // حفظ التوكن في الجلسة
            session(['auth_token' => $token]);
            
            // تسجيل دخول المستخدم
            Auth::login($credential->user);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'token'   => $token,
                    'redirect' => '/dashboard'
                ]);
            }
            
            return redirect('/dashboard');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
            ], 401);
        }
        
        return back()->withErrors([
            'username' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
        ]);
    }

    

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // حذف التوكن من الجلسة
            session()->forget('auth_token');
            // تسجيل الخروج
            Auth::logout();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح',
                'redirect' => '/login'
            ]);
        }
        
        return redirect('/login');
    }

    
   
}

