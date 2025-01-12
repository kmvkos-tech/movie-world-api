<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\VoteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    if (!$request->user()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
    $data = [
        'access_token' => $request->bearerToken(),
        'user' => $request->user()
    ];
    return response()->json($data);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/movies', [MovieController::class, 'store']);
    Route::post('/movies/{movie}/vote', [VoteController::class, 'vote']);
    Route::delete('/movies/{movie}/vote', [VoteController::class, 'retractVote']);


});

//******************* PUBLIC ROUTES *******************//
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/user/{user}', [MovieController::class, 'filterByUser']);
