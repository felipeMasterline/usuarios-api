<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




Route::get('/users' , [UserController::class, 'index']);
Route::post('/users-create' , [UserController::class, 'store']);
Route::put('/users-update' , [UserController::class, 'update']);
Route::delete('/users-destroy' , [UserController::class, 'destroy']);
