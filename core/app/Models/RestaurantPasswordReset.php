<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantPasswordReset extends Model
{
    protected $table = "restaurant_password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
