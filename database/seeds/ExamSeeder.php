<?php

use Illuminate\Database\Seeder;

use App\Exam;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
	{
		DB::table('exam')->delete();

        $examDBDir = __DIR__.'/../../exam_db/hajozasi_ismeretek/';

        $exam = file($examDBDir.'001_vitorlaskishajo.csv');
        array_shift($exam);

        $match = array();
        foreach($exam as $item) {
            list($question, $goodAnswer, $badAnswer1, $badanswer2, $picture) = str_getcsv($item, ',', '"');

            if (!empty($picture)) {
                if (!strpos($picture, '.jpg')) {
                    $picture = $picture.'.jpg';
                }

                if (!file_exists($examDBDir.$picture)) {
                    var_dump($question, $examDBDir.$picture);
                }
            }

            Exam::create(
                [
                    'type'        => 'sailboat',
                    'question'    => $question,
                    'good_answer' => $goodAnswer,
                    'bad_answer1' => $badAnswer1,
                    'bad_answer2' => $badanswer2,
                    'picture'     => $picture,
                ]
            );
        }
	}
}
