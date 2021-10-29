<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Effort;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
  public function store(Comment $comment, CommentRequest $request) {

  	$comment->fill($request->all());
  	$comment->user_id = Auth::user()->id;
  	$comment->save();

  	$effort_id = $request->effort_id;

  	return redirect()->route('efforts.show', ['effort' => $effort_id]);

  }

}
