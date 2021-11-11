<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class maker extends Model
{
   

    use HasFactory;
    protected $fillable = [

        'target_id',
        'active',
        'urlpattern',
        'htmlpattern',
        'maker', 
        'savetype', 
        'remoteapi', 
    ];


    public function posts()
    {
        return $this->hasMany('App\Models\post');
    }



}
