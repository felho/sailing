<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Exam;
use App\Practice;
use DB;

class ExamController extends Controller
{
	public function getRandomItem(Request $request, $type = null)
	{
        $pdo       = DB::connection()->getPdo();
        $groupName = $request->groupName;
        $userName  = $pdo->quote($request->userName);

        $joinFunction     = function($join) {
            $join->on('exam.id', '=', 'sub.question_id');
        };

        $buildQuery = function($where = []) use ($pdo, $userName) {
            foreach ($where as $field => &$value) {
                $value = ' AND field = ' . $pdo->quote;
            }

            $where = join("\n", $where);

            return "(
                select 
                    question_id, count(*) count 
                from 
                    practice 
                where 
                    is_right_answer > 0 
                    and user_name = $userName
                    $where
                group by question_id
            ) sub";
        };

        $predicate = [];

		if (!is_null($type)) {
			if ($groupName) {
				if ($type == '*') {
				    $predicate = ['group_name' => $groupName];
				} else {
				    $predicate = ['type' => $type, 'group_name' => $groupName];
				}
			} else {
				$predicate = ['type' => $type];
			}
		}

		$item = DB::table('exam')
            ->leftJoin(DB::raw($buildQuery($predicate)), $joinFunction)
            ->orderBy(DB::raw('coalesce(sub.count, 0)'))
            ->orderBy(DB::raw('rand()'))
            ->first();

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
