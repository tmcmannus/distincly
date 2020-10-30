<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function attributes(){
    	return $this->hasMany('App\Models\ProductsAttribute','product_id');
    }
}