<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Effort extends Model
{
	protected $fillable = [
		'title',
		'content',
		'reflection',
		'enthusiasm',
		'effort_time',
		'user_id',
		'goal_id',
		'id',
	];
    //
	public function goal(): BelongsTo
	{
		return $this->belongsTo('App\Models\Goal');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo('App\Models\User');
	}

	public function comments(): hasMany
	{
		return $this->hasMany('App\Models\Comment');
	}	

	public function likes():BelongsToMany
	{
		return $this->belongsToMany('App\Models\User', 'likes')->withTimestamps();
	}

	public function isLikedBy(?User $user):bool
	{
		return $user
			? (bool)$this->likes->where('id', $user->id)->count()
			: false;
	}

	public function getCountLikesAttribute(): int
	{
		return $this->likes->count();
	}

}



