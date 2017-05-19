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

        $examDBDir = __DIR__.'/../../exam_db/';

        // $this->processSimpleCsv($examDBDir.'hajozasi_ismeretek/', '001_kisgephajo.csv', 'motorboat');
        // $this->processSimpleCsv($examDBDir.'hajozasi_ismeretek/', '001_vitorlaskishajo.csv', 'sailboat');
	}

    private function processSimpleCsv($examDir, $csvFile, $type)
    {
        $exam = file($examDir.$csvFile);
        array_shift($exam);

        $match = array();
        foreach($exam as $item) {
            list($question, $goodAnswer, $badAnswer1, $badanswer2, $picture) = str_getcsv($item, ',', '"');

            if (!empty($picture)) {
                if (!strpos($picture, '.jpg')) {
                    $picture = $picture.'.jpg';
                }

                if (!file_exists($examDir.$picture)) {
                    var_dump($question, $examDir.$picture);
                }
            }

            $this->saveItem($type, $question, $goodAnswer, $badAnswer1, $badanswer2, $picture);
        }
    }

    private function saveItem($type, $question, $goodAnswer, $badAnswer1, $badanswer2, $picture)
    {
        Exam::create(
            [
                'type'        => $type,
                'question'    => $question,
                'good_answer' => $goodAnswer,
                'bad_answer1' => $badAnswer1,
                'bad_answer2' => $badanswer2,
                'picture'     => $picture,
            ]
        );        
    }
}
