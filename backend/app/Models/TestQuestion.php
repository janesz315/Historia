<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['questionId', 'answerId', 'userTestId'];
    public function question()
    {
        return $this->belongsTo(Question::class, 'questionId');
    }
    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answerId');
    }
    public function userTest()
    {
        return $this->belongsTo(UserTest::class, 'userTestId');
    }
}
