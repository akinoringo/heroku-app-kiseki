<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\Tag;

class TagService {

	/**
		* 目標のタグ名をすべて取得
		* @param Tag $tag
		* @return  Array
	*/
	public function getTagNamesByGoal($goal)
	{
		// Vue Tags Inputでは、タグ名にtextというキーが必要という仕様
    $tagNames = $goal->tags->map(function ($tag) {
        return ['text' => $tag->name];
    });	

    return $tagNames;
	}


	/**
		* すべてのタグ名を取得
		* @param Tag $tag
		* @return  Array
	*/
	public function getAllTagNames()
	{
    $allTagNames = Tag::all()->map(function ($tag) {
        return ['text' => $tag->name];
    });

    return $allTagNames;
	}

	/**
		* タグを目標にアタッチ
		* @param Goal $goal
		* @param Request $request
		* @param Tag $tag
		* @return  void
	*/
	public function storeTags($goal, $request)
	{
	  $request->tags->each(function ($tagName) use ($goal) {
	      $tag = Tag::firstOrCreate(['name' => $tagName]);
	      $goal->tags()->attach($tag);
	  });			
	}

	/**
		* 目標に紐づくタグを更新
		* @param Goal $goal
		* @param Request $request
		* @param Tag $tag
		* @return  void
	*/
	public function updateTags($goal, $request)
	{
    $goal->tags()->detach();

    $request->tags->each(function ($tagName) use ($goal) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $goal->tags()->attach($tag);
    });			
	}	


	/**
		* 軌跡が紐づいている目標のタグを取得
		* @param Effort $effort
		* @param Goal $goal
		* @param Tag $tag
		* @return  string
	*/	
	public function getHashtagsForShare($effort) {

		// 軌跡が紐づく目標タグの最初のタグを取得
		$tag_first = $effort->goal->tags->first() ?? null;

		// タグがある場合
		// Vue-Social-ShareのHashtagの書式("a,b,c")でタグを取得
		if ($tag_first !== null) {
			foreach ($effort->goal->tags as $tag) {

				if ($tag === $tag_first) {
					$hashtags = $tag_first->name;

				}
				if ($tag !== $tag_first) {

					$hashtags .= "," . $tag->name;
				}			
			}			

		} else { // タグがない場合

			$hashtags = "軌跡";

		}		

		return $hashtags;

	}
}