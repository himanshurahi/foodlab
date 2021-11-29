<?php

namespace App\Models;

use App\Http\Controllers\Restaurant\RestaurantAuthorizationController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vouchar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
