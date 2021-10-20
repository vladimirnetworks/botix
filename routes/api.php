<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





Route::apiResource('targets', 'App\Http\Controllers\TargetController');
Route::apiResource('targets.patterns', 'App\Http\Controllers\UrlpatternController');
Route::apiResource('targets.makers', 'App\Http\Controllers\MakerController');



Route::get('/crawl', 'App\Http\Controllers\crawlController@crawl');
Route::get('/make', 'App\Http\Controllers\crawlController@make');





/*
Route::prefix("targets")->group(function () {

    $c = 'App\Http\Controllers\TargetController@';
 
    Route::get('/', $c.'index');
    Route::post('/', $c.'store');
    Route::put('/{id}', $c.'update');
    Route::delete('/{id}', $c.'destroy');

});
*/







#Route::get('talgets', "App\Http\Controllers\TargetCont2@index");
#Route::get('talgets/{target}', "App\Http\Controllers\TargetCont2@show");




/*
Route::prefix("targetxx")->group(function () {

    Route::prefix('/{target_id}/patterns')->group(function () {

        Route::apiResource('/', 'App\Http\Controllers\UrlpatternController');

       
        Route::get('/', 'App\Http\Controllers\TargetController@viewpatterns');
        Route::post('/addnew', 'App\Http\Controllers\TargetController@addnewpattern');
        Route::post('/edit', 'App\Http\Controllers\TargetController@editpattern');
        Route::post('/delete', 'App\Http\Controllers\TargetController@deletepattern');
       

    });



    Route::prefix('/{target_id}/makers')->group(function () {

        Route::get('/', 'App\Http\Controllers\TargetController@viewmakers');
        Route::post('/addnew', 'App\Http\Controllers\TargetController@addnewmaker');
        Route::post('/edit', 'App\Http\Controllers\TargetController@editmaker');
        Route::post('/delete', 'App\Http\Controllers\TargetController@deletemaker');
    });
});
*/











