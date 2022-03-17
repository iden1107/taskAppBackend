<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\chartController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;

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

// ログイン関連
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return Auth::user();
});
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout']);
// Route::post('/register', [LoginController::class, 'register']);

// タスク関連
Route::get('/tags/{tag_id}',[TaskController::class, 'show']);
Route::post('/task/create',[TaskController::class, 'create']);
Route::post('/task/update/{id}', [TaskController::class, 'update']);
Route::post('/task/toggleUnfinished/{id}', [TaskController::class, 'toggleUnfinished']);
Route::post('/task/taskDelete/{id}', [TaskController::class, 'taskDelete']);
Route::get('/task/countUnfinished', [TaskController::class, 'countUnfinished']);

// タグ関連
Route::post('/tag/create',[TagController::class, 'create']);
Route::post('/tag/update/{id}',[TagController::class, 'update']);
Route::post('/tag/tagDelete/{id}',[TagController::class, 'tagDelete']);

Route::get('/tag/tagDelete/{id}',[TagController::class, 'tagDelete']);


// グラフ関連
Route::get('/chart/bar', [chartController::class, 'bar']);
Route::get('/chart/pie', [chartController::class, 'pie']);


Route::get('/',function(){
    return 'debag';
}
);
