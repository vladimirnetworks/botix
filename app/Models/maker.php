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
        'maker', /*
        
        return [

'title'=>"aaa",
'text'=>"bbb"

];
        
        */
        
    ];
}
