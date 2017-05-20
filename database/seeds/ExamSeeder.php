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

        $this->processSimpleCsv($examDBDir.'hajozasi_ismeretek/', '001_kisgephajo.csv', 'motorboat');
        $this->processSimpleCsv($examDBDir.'hajozasi_ismeretek/', '001_vitorlaskishajo.csv', 'sailboat');
        $this->processComplexCsv($examDBDir.'hajozasi_szabalyzat_kedvtelesi/', '001_Hajozasi_Szabalyzat_Kedvtelesi.csv', 'regulation');
	}

    private function processComplexCsv($examDir, $csvFile, $type)
    {
        $exam = file($examDir.$csvFile);
        array_shift($exam);

        $match = array();
        foreach($exam as $key => $item) {
            list($origCsvId, $question, $answer1, $isRight1, $answer2, $isRight2, $answer3, $isRight3, $picture) = str_getcsv($item, ',', '"');

            if (!empty($picture)) {
                if (!strpos($picture, '.jpg') && !strpos($picture, '.png')) {
                    $picture = $picture.'.jpg';
                }

                if (!file_exists($examDir.$picture)) {
                    var_dump($question, $examDir.$picture);
                }
            }

            switch([$isRight1, $isRight2, $isRight3]) {
                case ['I', 'N', 'N']:
                case ['I', 'N', '']:
                    list($goodAnswer, $badAnswer1, $badanswer2) = [$answer1, $answer2, $answer3]; break;
                case ['N', 'I', 'N']:
                case ['N', 'I', '']:
                    list($badAnswer1, $goodAnswer, $badanswer2) = [$answer1, $answer2, $answer3]; break;
                case ['N', 'N', 'I']:
                    list($badAnswer1, $badanswer2, $goodAnswer) = [$answer1, $answer2, $answer3]; break;
                    continue;
                default:
                    var_dump($key, [$isRight1, $isRight2, $isRight3]);
            }

            switch (true) {
                case $origCsvId <= 65:
                    $groupName = 'HSZ 01 - Általános vezetési ismeretek'; break;
                case $origCsvId <= 170:
                    $groupName = 'HSZ 02 - Hajóbiztonsági ismeretek'; break;
                case $origCsvId <= 197:
                    $groupName = 'HSZ 03 - Környezetvédelmi ismeretek'; break;
                case $origCsvId <= 275:
                    $groupName = 'HSZ 04 - Víziút, hajóút, kitűzés'; break;
                case $origCsvId <= 382:
                    $groupName = 'HSZ 05 - Vízi közlekedés irányítása - 1'; break;
                case $origCsvId <= 491:
                    $groupName = 'HSZ 06 - Vízi közlekedés irányítása - 2'; break;
                case $origCsvId <= 729:
                    $groupName = 'HSZ 07 - Hajózási szabályok - 1'; break;
                default:
                    $groupName = '';
            }

            $this->saveItem($origCsvId, $type, $question, $goodAnswer, $badAnswer1, $badanswer2, $picture, $groupName);
        }
    }

    private function processSimpleCsv($examDir, $csvFile, $type)
    {
        $exam = file($examDir.$csvFile);
        array_shift($exam);

        $match = array();
        foreach($exam as $item) {
            list($origCsvId, $group, $question, $goodAnswer, $badAnswer1, $badanswer2, $picture) = str_getcsv($item, ',', '"');

            if (!empty($picture)) {
                if (!strpos($picture, '.jpg')) {
                    $picture = $picture.'.jpg';
                }

                if (!file_exists($examDir.$picture)) {
                    var_dump($question, $examDir.$picture);
                }
            }

            if ($csvFile == '001_vitorlaskishajo.csv') {
                switch ($group) {
                    case 2:
                        $groupName = 'Hajózási ismeretek - Közös vitorlás és kisgéphajós (2. nap)'; break;
                    case 4:
                        $groupName = 'Hajózási ismeretek - Vitorlás kérdések 4. nap'; break;
                    default:
                        $groupName = '';
                }
            } else {
                $groupName = '';
            }

            $this->saveItem($origCsvId, $type, $question, $goodAnswer, $badAnswer1, $badanswer2, $picture, $groupName);
        }
    }

    private function saveItem($origCsvId, $type, $question, $goodAnswer, $badAnswer1, $badanswer2, $picture, $groupName = '')
    {
        Exam::create(
            [
                'orig_csv_id' => $origCsvId,
                'type'        => $type,
                'group_name'  => $groupName,
                'question'    => $question,
                'good_answer' => $goodAnswer,
                'bad_answer1' => $badAnswer1,
                'bad_answer2' => $badanswer2,
                'picture'     => $picture,
            ]
        );        
    }
}
