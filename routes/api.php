<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware(['auth:api', 'verify.user'])->group(function () {

    //just check token based and middleware based
    Route::get('blogs', [BlogController::class, 'index']);
    Route::post('blogs', [BlogController::class, 'store']);
    Route::put('blogs/{id}', [BlogController::class, 'update']);
    Route::delete('blogs/{id}', [BlogController::class, 'destroy']);

    //check A user multiple role
    Route::get('user-info', [AuthController::class, 'userInfo']);

    //Role management routes
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);

    //Assign Role
    Route::post('assign-roles/{userId}', [RoleController::class, 'assignRoles']);

});

