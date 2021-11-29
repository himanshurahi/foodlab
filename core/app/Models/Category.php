<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function restaurant()
    {
        return $this->belongsTo(Category::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
