<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\UserController;
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

Route::post('/token', [ UserController::class, 'generateUserToken']);

Route::group([ 'middleware' => ['auth:sanctum'] ], function() {
    Route::apiResource('/patients', UserController::class);

    Route::apiResource('/patients/{patient}/notes', NoteController::class);

    Route::get('/user', [ UserController::class, 'getAuthUser']);

    Route::post('/logout', [ UserController::class, 'logout' ]);
});
