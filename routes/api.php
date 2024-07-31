<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// 教师路由组
Route::group(['middleware' => 'auth:teacher-api','prefix' => 't'], function (Illuminate\Routing\Router $router) {
    $router->get('/me','App\Http\Controllers\Api\AuthController@me');

    $router->resource('courses','App\Http\Controllers\Api\CourseController');
});

// 学生路由组
Route::group(['middleware' => 'auth:teacher','prefix' => 's'], function (Illuminate\Routing\Router $router) {
    $router->get('/me','App\Http\Controllers\Api\AuthController@me');
});

Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
