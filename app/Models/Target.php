<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Target extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'url',
        'lastseen'
    ];
    
     public function urlpatterns()
    {
        return $this->hasMany('App\Models\urlpattern');
    }
    

    public function pages()
    {
        return $this->hasMany('App\Models\page');
    }


    public function makers()
    {
        return $this->hasMany('App\Models\maker');
    }
    
}
