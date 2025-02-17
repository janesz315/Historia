<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'level', 'text'];
    public function questions()
    {
        return $this->hasMany(Question::class, 'categoryId');
    }
    public function sources()
    {
        return $this->hasMany(Source::class, 'categoryId');
    }
}
