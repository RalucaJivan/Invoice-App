<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    public $timestamps = false;
    protected $table = 'products';
    protected $fillable = [
        'pk_id',
        'fk_invoice',
        'description',
        'quantity',
        'price'
    ];
}