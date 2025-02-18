<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}