<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'questionTypeId', 'categoryId'];
    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'questionTypeId');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }
    public function answers()
    {
        return $this->hasMany(Answer::class, 'questionsId');
    }
}
