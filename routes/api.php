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

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/auth'

], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\Api\V1\AuthController::class, 'refresh'])->name('refresh');
    Route::get('me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me'])->name('me');
    Route::post('register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register'])->name('register');
});


Route::apiResource('v1/usuarios', App\Http\Controllers\Api\V1\UsuarioController::class)->middleware('api');
Route::get('v1/usuario/{usuario}', [App\Http\Controllers\Api\V1\UsuarioController::class, 'showByUser'])->middleware('api');
Route::apiResource('v1/entradas', App\Http\Controllers\Api\V1\EntradaController::class)->middleware('api');
Route::apiResource('v1/categorias', App\Http\Controllers\Api\V1\CategoriaController::class)->middleware('api');
Route::get('v1/categoria/{categoria}', [App\Http\Controllers\Api\V1\CategoriaController::class, 'showByCategory'])->middleware('api');

//Route::apiResource('v1/comentarios', App\Http\Controllers\Api\V1\ComentarioController::class)->middleware('api');
