<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;

class TagController extends Controller
{
    public function create(Request $request){
        $user = Auth::user();
        $user->id = 4;
        $newTag =  Tag::create([
            'title' => $request->title,
            'user_id' => $user->id
        ]);
        return $newTag;
    }
}
