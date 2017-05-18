<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Exam;
use App\Practice;

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

	public function savePractice($questionId, Request $request)
	{
		$foo = Practice::create(
            [
                'user_name'       => $request->userName,
                'question_id'     => $questionId,
                'is_right_answer' => $request->isRightAnswer,
                'answered_at'     => \Carbon\Carbon::now()->toDateTimeString(),
            ]
        );

		return response()->json([$foo, \Carbon\Carbon::now()->toDateTimeString()]);
	}
}
