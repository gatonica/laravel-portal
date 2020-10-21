<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\YelpController;
use App\Http\Controllers\Auth\LoginController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user/login', [LoginController::class, 'loginUser']);

Route::group([
    'middleware' => 'auth:api'
  ], function() {

    Route::get('user/notifications', [NotificationController::class, 'getUserNotifications']);
    Route::post('user/notifications', [NotificationController::class, 'createUserNotifications']);
    Route::post('user/notifications/{notificationId}', [NotificationController::class, 'updateUserNotifications']);
    Route::delete('user/notifications/{notificationId}', [NotificationController::class, 'deleteUserNotifications']);

  });

  Route::group([
    'prefix' => 'yelp'
], function () {  

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('search/{queryString}/location/{location}', [YelpController::class, 'searchYelp']);
    });
});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});
