<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Tag;

class TagController extends Controller
{
    public function create(Request $request){
        $user = Auth::user();
        $newTag =  Tag::create([
            'title' => $request->title,
            'user_id' => $user->id,
            'color_label' => $request->colorLabel,
        ]);
        return $newTag;
    }

    public function update(Request $request,$id)
    {
        $user = Auth::user();
        Tag::where('user_id', $user->id)->where('id',$id)->update([
            'title' => $request->title,
            'color_label' => $request->colorLabel,
        ]);
    }

    public function tagDelete($id)
    {
        $user = Auth::user();

        // タグに付随するタスクがあるかないかを判断
        $tasks = Task::where('user_id', $user->id)->select('tasks.*', 'tags.title as tags_title','tags.id as tags_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.id')
            ->where('tags.id',$id)
            ->get();

        // なければタグを物理削除、あればタグを論理削除
        // ただし未完のタスクは物理削除する（クライアント側から見えないタスクが残り続け、右上のバッジにカウントされてしまうため）
        if($tasks->isEmpty()){
            Tag::where('user_id', $user->id)->where('id', $id)->delete();
        }else{
            // 論理削除
            Task::where('user_id', $user->id)
                ->where('tag_id',$id)
                ->where('unfinished', 1)
                ->update([
                    'status' => 0
                ]);

            // 物理削除
            Task::where('user_id', $user->id)
                ->where('tag_id', $id)
                ->where('unfinished', 0)
                ->delete();

            // タグは論理削除する
            Tag::where('user_id', $user->id)
                ->where('id',$id)->update([
                'status' => 0,
            ]);
        }
    }
}
