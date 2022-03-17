<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use App\Models\Task;



// !!!! $userがnullになってしまうのでうまくいかない
class TaskController extends Controller
{
    public function show($tag_id){
        $user = Auth::user();
        $tags = Tag::where('user_id', $user->id)->where('status', 1)->get();
        $tag = Tag::where('user_id', $user->id)->where('status', 1)->where('id',$tag_id)->get();
        if($tag_id === 'all'){
            $tasks = Task::select('tasks.*', 'tags.title as tags_title','tags.id as tags_id',)
            ->leftJoin('tags','tasks.tag_id','=','tags.id')
            ->where('tags.user_id',$user->id)
            ->where('tasks.status',1)
            ->get();
        }else{
            $tasks = Task::select('tasks.*','tags.title as tags_title', 'tags.id as tags_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.id')
            ->where('tags.user_id', $user->id)
            ->where('tags.id', $tag_id)
            ->where('tasks.status', 1)
            ->get();
        }
        return compact('tags','tasks','tag','user');
    }

    public function create (Request $request){
        $user = Auth::user();
        $newTask =  Task::create([
            'user_id' => $user->id,
            'tag_id' => $request->tag_id,
            'title' => $request->title,
            'deadline_date' => $request->deadline_date,
            'memo' => $request->memo,
            'unfinished'=>0,
            'status'=>1,
        ]);
        return compact('user','newTask');
    }

    public function update (Request $request,$id){
        // ただの更新ならupdated_atを自動更新させない
        $user = Auth::user();
        DB::table('tasks')->where('id', $id)->where('user_id',$user->id)->update([
            'tag_id' => $request->tag_id,
            'title' => $request->title,
            'deadline_date' => $request->deadline_date,
            'memo' => $request->memo,
        ]);
    }

    public function toggleUnfinished (Request $request, $id)
    {
        // unfinishedの更新ならupdated_atを自動更新させる
        $user = Auth::user();
        Task::where('id', $id)->where('user_id', $user->id)->update([
            'unfinished' => $request->unfinished,
        ]);
    }

    public function taskDelete (Request $request, $id)
    {

        if($request->unfinished){
            Task::where('id', $id)->update([
                'status' => 0
            ]);
        }else{
            Task::where('id', $id)->delete();
        }
    }

    public function countUnfinished ()
    {
        $today = date("Y-m-d");
        $user = Auth::user();
        $countUnfinished = Task::where('user_id', $user->id)
            ->where('deadline_date','<=',$today)
            ->where('unfinished',0)
            ->where('status',1)
            ->get();
        return compact('countUnfinished');
    }
}

// class TaskController extends Controller
// {
//     public function show($tag_id){
//         $user = Auth::user();
//         $tags = Tag::where('user_id', 4)->get();
//         $tag = Tag::where('user_id', 4)->where('id',$tag_id)->get();
//         if($tag_id === 'all'){
//             $tasks = Task::select('tasks.*', 'tags.title as tags_title','tags.id as tags_id')->leftJoin('tags','tasks.tag_id','=','tags.id')->where('tags.user_id',4)->where('status',1)->get();
//         }else{
//             $tasks = Task::select('tasks.*', 'tags.title as tags_title', 'tags.id as tags_id')->leftJoin('tags', 'tasks.tag_id', '=', 'tags.id')->where('tags.user_id', 4)->where('tags.id', $tag_id)->where('status', 1)->get();
//         }
//         return compact('tags','tasks','tag');
//     }

//     public function create (Request $request){
//         $user = Auth::user();
//         $newTask =  Task::create([
//             'user_id' => 4,
//             'tag_id' => $request->tag_id,
//             'title' => $request->title,
//             'deadline_date' => $request->deadline_date,
//             'memo' => $request->memo,
//             'unfinished'=>0,
//             'status'=>1,
//         ]);
//         return compact('user','newTask');
//     }

//     public function update (Request $request,$id){
//         // ただの更新ならupdated_atを自動更新させない
//         DB::table('tasks')->where('id', $id)->update([
//             'tag_id' => $request->tag_id,
//             'title' => $request->title,
//             'deadline_date' => $request->deadline_date,
//             'memo' => $request->memo,
//         ]);
//     }

//     public function toggleUnfinished (Request $request, $id)
//     {
//         // unfinishedの更新ならupdated_atを自動更新させる
//         Task::where('id', $id)->update([
//             'unfinished' => $request->unfinished,
//         ]);
//     }

//     public function taskDelete (Request $request, $id)
//     {
//         if($request->unfinished){
//             Task::where('id', $id)->update([
//                 'status' => 0
//             ]);
//         }else{
//             Task::where('id', $id)->delete();
//         }
//     }

//     public function countUnfinished ()
//     {
//         $today = date("Y-m-d");
//         $user = Auth::user();
//         $countUnfinished = Task::where('user_id', 4)->where('deadline_date','<=',$today)->where('unfinished',0)->where('status',1)->get();
//         return compact('countUnfinished');
//     }
// }
