<?php

namespace App\Models; // /user atunci importez clasa user

use \Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $table = 'user';
    protected $fillable = [
        'username',
        'email',
        'password'
    ];
}