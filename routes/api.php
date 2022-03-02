<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Task;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return Auth::user();
});
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/register', [LoginController::class, 'register']);


Route::get('/tags/{tag_id}', function($tag_id){
    $user = Auth::user();
    if($tag_id === 'all'){
        $tags = Tag::where('user_id', $user->id)->get();
        $tasks = Task::select('tasks.*', 'tags.title as tags_title')->leftJoin('tags', 'tasks.tag_id', '=', 'tags.id')->where('tasks.user_id', $user->id)->get();
    }else{
        $tags = Tag::where('user_id', $user->id)->get();
        $tasks = Task::select('tasks.*', 'tags.title as tags_title')->leftJoin('tags', 'tasks.tag_id', '=', 'tags.id')->where('tasks.user_id', $user->id)->where('tasks.tag_id', $tag_id)->get();
    }
    return compact('tags','tasks');
});
