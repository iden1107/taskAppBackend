<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class chartController extends Controller
{
    public function bar(){
        $year = date("Y");
        $month = date("m");
        $user = Auth::user();
        $tasks = Task::where('user_id',$user->id)
            ->where('unfinished',1)
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->orderBy('updated_at')
            ->get()
            ->groupBy(function ($row) {
                return abs($row->updated_at->format('d'));
            })
            ->map(function ($day) {
                return $day->count();
            });
        return compact('tasks');
    }

    public function pie(){
        $year = date("Y");
        $month = date("m");
        $user = Auth::user();
        return  Task::leftJoin('tags', 'tasks.tag_id', '=', 'tags.id')
            ->where('tasks.user_id', $user->id)
            ->where('unfinished',1)
            ->whereYear('tasks.updated_at', $year)
            ->whereMonth('tasks.updated_at', $month)
            ->select('tasks.tag_id', 'tags.title as tags_title','color_label')
            ->selectRaw('COUNT(tasks.title) as count')
            ->groupBy('tasks.tag_id', 'tags_title', 'color_label')
            ->orderBy('count','desc')
            ->get();
        }
}
