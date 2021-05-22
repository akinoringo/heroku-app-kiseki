<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
  public function show(string $name)
  {
    $tag = Tag::where('name', $name)->first();

    $effortsOnTag = Effort::whereHas('goal.tags', function($tags) use ($name){
    	$tags->where('name', $name);
    })->orderBy('created_at', 'desc')->get();

    return view('tags.show', ['tag' => $tag, 'effortsOnTag' => $effortsOnTag]);
  }  
}
