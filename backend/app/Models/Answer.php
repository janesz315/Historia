<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['answer', 'questionId', 'rightAnswer'];
    public function question()
    {
        return $this->belongsTo(Question::class, 'questionId');
    }

    protected function casts(): array
    {
        return [
            'rightAnswer' => 'boolean'
        ];
    }
}
