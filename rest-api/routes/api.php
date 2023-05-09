<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    return $request->user();
});
// Route::apiResource("/v1/auth", AuthController::class);
Route::get("/", [AuthController::class, "index"]);
Route::post("/v1/auth/login", [AuthController::class, "login"]);
Route::post("/v1/auth/logout", [AuthController::class, "logout"]);
Route::post("/v1/auth/consultations", [AuthController::class, "konsul"])->middleware("isExist");;
Route::get("/v1/auth/consultations/{consultations::id}", [AuthController::class, "getKonsul"]);
Route::get("/v1/auth/spots", [AuthController::class, "getAllSpot"])->middleware("isExist");
Route::get("/v1/auth/spots/{spots:id}", [AuthController::class, "getSpotId"])->middleware("isExist");
Route::post("/v1/auth/vaccinations", [AuthController::class, "VaccinesRegis"]);
Route::get("/v1/auth/vaccinations", [AuthController::class, "getAllVaccine"]);
