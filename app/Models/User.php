<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        
        'number',
        'first_name',
        'middle_name',
        'last_name',  
         'date_of_birth', 
        'mother_name', 
        'birth_day',
        'national_number',
        'gender',
        'balance'
        
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // هذه الدوال يجب أن تكون موجودة داخل الكلاس User

public function getJWTIdentifier()
{
    return $this->getKey(); // عادةً يرجع الـ id
}

public function getJWTCustomClaims()
{
    return []; // لو عندك claims إضافية، تحطها هنا
}
public function fcmTokens()
{
    return $this->hasMany(FcmToken::class);
}

public function doctor()
{
    return $this->hasOne(Doctors::class);
}

}
