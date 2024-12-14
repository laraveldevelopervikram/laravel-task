<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1', 'namespace' => 'v1'], function () {
        require(__DIR__ . '/API/v1/api.php');
    });
});
