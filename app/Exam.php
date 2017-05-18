<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
	public $timestamps = false;

	protected $table = 'exam';

	protected $fillable = [
		'type',
		'question',
		'good_answer',
		'bad_answer1',
		'bad_answer2',
		'picture',
	];
}
