<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
	public $timestamps = false;

	protected $table = 'practice';

	protected $fillable = [
		'user_name',
		'question_id',
		'is_right_answer',
		'answered_at',
	];
}
