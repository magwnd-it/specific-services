<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'subject',
        'product',
        'priority',
        'description',
        'attachfile',
    ];

    // Ticket has Many replies
    public function replies() 
    {
        return $this->hasMany('App\Models\Reply');
    }

    
    // Ticket has one user
    function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }


}