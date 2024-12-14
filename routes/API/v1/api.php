<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\UserManagementController;

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
Route::group(['prefix' => 'user'], function () {
    Route::post('create', [UserManagementController::class, 'createUser']);
});