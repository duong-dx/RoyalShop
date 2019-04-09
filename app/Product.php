<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','slug','code','user_id','category_id','brand_id','ram','weight','screen_size','pin','front_camera','rear_camera','operating_system','view_count','warranty_time'];
}
