<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $table = "cart";
    public $guarded = [];

    public static $rules = [
        'quantity' => 'required',
    ];

    public static $editRules = [
        'product_id' => 'required',
        'quantity' => 'required'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
