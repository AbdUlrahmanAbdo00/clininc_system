<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminCredential;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Closure): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من وجود التوكن في localStorage (سيتم إرساله في header)
        $token = $request->header('Authorization');
        
        if (!$token) {
            // محاولة الحصول من session
            $token = session('auth_token');
        }
        
        if (!$token) {
            // إذا لم يكن هناك توكن، توجيه لصفحة تسجيل الدخول
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect('/login');
        }
        
        // إزالة "Bearer " من التوكن إذا كان موجود
        $token = str_replace('Bearer ', '', $token);
        
        try {
            // التحقق من صحة التوكن
            $user = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            
            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Invalid token'], 401);
                }
                return redirect('/login');
            }
            
            // تسجيل دخول المستخدم
            Auth::login($user->tokenable);
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Token validation failed'], 401);
            }
            return redirect('/login');
        }
        
        return $next($request);
    }
}

