<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Goal extends Model
{
    //
	protected $fillable = [
		'title',
		'content',
		'goal_time',
		'user_id',
		'deadline'
	];


	public function user(): BelongsTo
	{
		return $this->belongsTo('App\Models\User');
	}

  public function efforts()
  {
      return $this->hasMany('App\Models\Effort');
  }

}
