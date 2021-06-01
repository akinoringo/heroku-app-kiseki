<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\Tag;

class TagService {

	/**
		* すべてのタグ名を取得
		* @param Tag $tag
		* @return  string
	*/
	public function getAllTagNames()
	{
    $allTagNames = Tag::all()->map(function ($tag) {
        return ['text' => $tag->name];
    });

    return $allTagNames;
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