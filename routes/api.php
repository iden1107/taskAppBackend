<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
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
    $tags = Tag::where('user_id', $user->id)->get();
    if($tag_id === 'all'){
        $tasks = Tag::select('tasks.*', 'tags.title as tags_title','tags.id as tags_id')->leftJoin('tasks','tags.id','=','tasks.tag_id')->where('tags.user_id',$user->id)->get();
    }else{
        $tasks = Tag::select('tasks.*','tags.title as tags_title', 'tags.id as tags_id')->leftJoin('tasks', 'tags.id','=','tasks.tag_id')->where('tags.user_id', $user->id)->where('tags.id', $tag_id)->get();
    }
    return compact('tags','tasks');
});

Route::post('/tag/create', function(Request $request){
    $user = Auth::user();
    Tag::create([
        'title' => $request->title,
        'user_id' => $user->id
    ]);
    $tags = Tag::where('user_id', $user->id)->get();
    return $tags;
});

Route::get('/tags/all', function () {
    return 'hoge';
});
