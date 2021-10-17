<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class maker_history extends Model
{
    protected $table = 'maker_history';

    use HasFactory;
    protected $fillable = [
        'target_id',
        'maker_id',
        'url',
    ];
}
