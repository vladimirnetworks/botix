<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class urlpattern extends Model
{
    use HasFactory;
    protected $fillable = [
        'target_id',
        'pattern',
        'whereParentpattern',
        'type',
        'savepattern'
    ];


    public function target()
    {
        return $this->belongsTo('App\Models\Target');
    }

}
