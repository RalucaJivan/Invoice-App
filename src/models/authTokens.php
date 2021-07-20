<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class AuthTokens extends Model
{
    public $timestamps = true;
    protected $table = 'authTokens';
    protected $fillable = [
        'pk_id',
        'fk_user',
        'key',
        'created_at',
        'updated_at'
    ];
}