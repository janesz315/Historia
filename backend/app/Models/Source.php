<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;
    protected $fillable = ['categoryId', 'sourceLink', 'note'];
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }
}
