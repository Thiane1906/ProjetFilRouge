<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\SessionCoursController;
use App\Http\Controllers\UserController;
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
Route::apiResource('/inscription',UserController::class);

Route::apiResource('/module',ModuleController::class);
Route::apiResource('/salle',SalleController::class);
Route::apiResource('/session',SessionCoursController::class);
Route::apiResource('/classes',ClasseController::class);
Route::get('/profs',[UserController::class,'getProfs']);
Route::get('/etudiant',[UserController::class,'getEtudiants']);
Route::get('/getclasses/{id}',[CoursController::class,'getClasseByCours']);
Route::get('/getIdClasse/{id}',[CoursController::class,'getIdClasseCoursByCours']);
Route::get('/name/{name}',[SessionCoursController::class,'getCoursByProfName']);

Route::get('/cours/{id}',[CoursController::class,'getProfBYModule']);
Route::apiResource('/cours',CoursController::class);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    Route::get('/user', [UserController::class, 'user']);
    return $request->user();
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);

