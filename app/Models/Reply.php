<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'replay_body',
    ];


    // Reply has one user
    function user () 
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    
}