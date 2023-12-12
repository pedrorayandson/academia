<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\TreinoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerificaTokenSUAP;
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
    return $request->user();
});

//Usuários

Route::get('/users', [UserController::class, 'index']);

Route::get('/user/{id}', [UserController::class, 'show']);

Route::post('/user/create', [UserController::class, 'store'])->middleware(VerificaTokenSUAP::class);

Route::put('/user/edit/{id}', [UserController::class, 'update'])->middleware(VerificaTokenSUAP::class);

Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->middleware(VerificaTokenSUAP::class);

//Aluno

Route::get('/alunos', [AlunoController::class, 'index']);

Route::get('/aluno/{id}', [AlunoController::class, 'show']);

Route::post('/aluno/create', [AlunoController::class, 'store'])->middleware(VerificaTokenSUAP::class);

Route::put('/aluno/edit/{id}', [AlunoController::class, 'update'])->middleware(VerificaTokenSUAP::class);

Route::delete('/aluno/delete/{id}', [AlunoController::class, 'destroy'])->middleware(VerificaTokenSUAP::class);

//Treinos

Route::get('/treinos', [TreinoController::class, 'index']);

Route::get('/treino/{id}', [TreinoController::class, 'show']);

Route::post('/treino/create', [TreinoController::class, 'store'])->middleware(VerificaTokenSUAP::class);

Route::put('/treino/edit/{id}', [TreinoController::class, 'update'])->middleware(VerificaTokenSUAP::class);

Route::delete('/treino/delete/{id}', [TreinoController::class, 'destroy'])->middleware(VerificaTokenSUAP::class);
