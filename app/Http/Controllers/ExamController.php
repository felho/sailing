<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Exam;

class ExamController extends Controller
{
	public function getRandomItem($type = null)
	{
		if (is_null($type)) {
			$item = Exam::inRandomOrder()->first();
		} else {
			$item = Exam::where('type', '=', $type)->inRandomOrder()->first();
		}

		if ($item->picture) {
			$item->picture = '/exam_db/hajozasi_ismeretek/'.$item->picture;
		}

		return response()->json($item);
	}
}
