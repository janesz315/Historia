<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;
    protected $fillable = ['userId', 'testName', 'score'];
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function testQuestions()
    {
        return $this->hasMany(TestQuestion::class, 'userTestId');
    }
}
