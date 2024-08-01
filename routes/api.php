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
    $router->resource('invoices','App\Http\Controllers\Api\InvoiceController');
    $router->resource('students','App\Http\Controllers\Api\StudentController');
    $router->post('invoices/{invoice}/send','App\Http\Controllers\Api\InvoiceController@send');
});

// 学生路由组
Route::group(['middleware' => 'auth:student-api','prefix' => 's'], function (Illuminate\Routing\Router $router) {
    $router->get('/me','App\Http\Controllers\Api\AuthController@me');
    $router->resource('invoices','App\Http\Controllers\Api\Student\InvoiceController');
    $router->resource('courses','App\Http\Controllers\Api\Student\CourseController');
    $router->resource('students','App\Http\Controllers\Api\Student\StudentController');

    $router->post('/invoices/{invoice}/card-pay','App\Http\Controllers\Api\Student\OmiseController@card');
});

Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
