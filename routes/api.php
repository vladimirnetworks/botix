<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/alive', function (Request $request) {
    sleep(0);
    return array("alive" => rand(0, 99999));
});


Route::get('/alivez', 'App\Http\Controllers\gozmelcontroller@index');

Route::get('/listqwe', function (Request $request) {

    for ($i = 0; $i <= 5; $i++) {
        $retlist[] = ['id' => rand(10, 99), "url" => "https://www." . rand(1000, 9999) . ".ir"];
    }

    return $retlist;
});


Route::get('/list2', function (Request $request) {

    for ($i = 0; $i <= 5; $i++) {
        $retlist[] = ['id' => rand(10, 99), "url" => "https://www.ggg.ir" . $_GET['page']];
    }

    return $retlist;
});


Route::get('/list25', function (Request $request) {

    for ($i = 0; $i <= 5; $i++) {
        $retlist[] = ['id' => rand(10, 99), "title" => "https://www.ggg.ir", "vang" => rand(0, 9)];
    }

    return ["data" => $retlist];
});







Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("targets")->group(function () {

    Route::get('/', 'App\Http\Controllers\TargetController@list');

    Route::post('/addnew', 'App\Http\Controllers\TargetController@addnew');
    Route::post('/edit', 'App\Http\Controllers\TargetController@edit');
    Route::post('/delete', 'App\Http\Controllers\TargetController@delete');
});


Route::prefix("target")->group(function () {

    Route::prefix('/{target_id}/patterns')->group(function () {

        Route::get('/', 'App\Http\Controllers\TargetController@viewpatterns');
        Route::post('/addnew', 'App\Http\Controllers\TargetController@addnewpattern');
        Route::post('/edit', 'App\Http\Controllers\TargetController@editpattern');
        Route::post('/delete', 'App\Http\Controllers\TargetController@deletepattern');
    });
});



Route::get('/crawl', 'App\Http\Controllers\crawlController@crawl');
