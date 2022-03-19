<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SingleController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->latest()->paginate(15);
        return view('single',compact(['post','comments']));
    }

    public function save_comment(Request $request,Post $post)
    {
        $post->comments()->create([
            'user_id' => \Auth::user()->id,
            'content' => $request->content,
        ]);

        // return redirect(route('single.index',$post->id));
        return \response()->json([
            'success' => true
        ]);
    }
}
