<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Effort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
  public function store(Comment $comment, Request $request) {

  	$comment->user_id = Auth::user()->id;
  	$comment->effort_id = $request->effort_id;
  	$comment->content = $request->content;
  	$comment->save();

  	$effort_id = $request->effort_id;

  	return redirect()->route('efforts.show', ['effort' => $effort_id]);

  }

}
