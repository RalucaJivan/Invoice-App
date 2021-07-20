<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = false;
    protected $table = 'invoice';
    protected $fillable = [
        'pk_id',
        'fk_user',
        'date',
        'due_date',
        'user_info',
        'client_info',
        'image',
        'tax',
        'note',
        'terms'
    ];
}