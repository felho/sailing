<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Exam;
use App\Practice;

class ExamController extends Controller
{
	public function getRandomItem(Request $request, $type = null)
	{
		$groupName = $request->groupName;

		if (is_null($type)) {
			$item = Exam::inRandomOrder()->first();
		} else {
			if ($groupName) {
				$item = Exam::where('type', '=', $type)->where('group_name', '=', $groupName)->inRandomOrder()->first();
			} else {
				$item = Exam::where('type', '=', $type)->inRandomOrder()->first();
			}
		}

		if ($item->picture) {
			if ($item->type == 'regulation') {
				$item->picture = '/exam_db/hajozasi_szabalyzat_kedvtelesi/'.$item->picture;
			} else {
				$item->picture = '/exam_db/hajozasi_ismeretek/'.$item->picture;
			}
		}

		$options = [
			['text' => $item->good_answer, 'isRight' => true],
			['text' => $item->bad_answer1, 'isRight' => false],
		];
		if ($item->bad_answer2) {
			$options[] = ['text' => $item->bad_answer2, 'isRight' => false];
		}
		shuffle($options);

		return response()->json(array(
			'orig_csv_id' => $item->orig_csv_id,
			'question' => $item->question,
			'question_id' => $item->id,
			'options' => $options,
			'picture' => $item->picture ?: null,
		));
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
