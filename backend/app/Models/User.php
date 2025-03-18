<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = ['name', 'roleId', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    public function role()
    {
        return $this->belongsTo(Role::class, 'roleId');
    }
    public function userTests()
    {
        return $this->hasMany(UserTest::class, 'userId');
    }

     // A jelszó titkosítása a mentés előtt
     protected static function booted()
     {
         static::creating(function ($user) {
             if (!empty($user->password)) {
                 $user->password = Hash::make($user->password);
             }
         });
     
         static::updating(function ($user) {
             // Csak akkor hash-eljük újra a jelszót, ha tényleg megváltozott
             if ($user->isDirty('password') && !empty($user->password)) {
                 $user->password = Hash::make($user->password);
             }
         });
     }
     
}