<?php

use App\Http\Controllers\Api\OutpassController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\StudentController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
        
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
    Route::controller(OutpassController::class)->prefix("outpass")->group(function(){
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::post('/change-outpass-status', 'outpassStatus');
    });
});

Route::controller(StudentController::class)->prefix("student")->group(function(){
    Route::post('/register', 'register');
    Route::post('login', 'login');
});