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
        'type',
        'savepattern'
    ];
}
