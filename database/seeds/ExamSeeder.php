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
                    $groupName = 'HSZ 01 - Általános vezetési ismeretek (1. nap)'; break;
                case $origCsvId <= 170:
                    $groupName = 'HSZ 02 - Hajóbiztonsági ismeretek (1. nap)'; break;
                case $origCsvId <= 197:
                    $groupName = 'HSZ 03 - Környezetvédelmi ismeretek (1. nap)'; break;
                case $origCsvId <= 275:
                    $groupName = 'HSZ 04 - Víziút, hajóút, kitűzés (1. nap)'; break;
                case $origCsvId <= 382:
                    $groupName = 'HSZ 05 - Vízi közlekedés irányítása - 1 (1. nap)'; break;
                case $origCsvId <= 491:
                    $groupName = 'HSZ 06 - Vízi közlekedés irányítása - 2 (2. nap)'; break;
                case $origCsvId >= 495 && $origCsvId <= 647:
                    $groupName = 'HSZ 07 - Hajózási szabályok - 1 - Dummy (2. nap)'; break;
                case $origCsvId <= 729:
                    $groupName = 'HSZ 07 - Hajózási szabályok - 1 (2. nap)'; break;
                case $origCsvId >= 732 && $origCsvId <= 738:
                case $origCsvId >= 740 && $origCsvId <= 866:
                    $groupName = 'HSZ 08 - Hajózási szabályok - 2 - Dummy (3. nap)'; break;
                case $origCsvId <= 866:
                    $groupName = 'HSZ 08 - Hajózási szabályok - 2 (3. nap)'; break;
                case $origCsvId <= 918:
                    $groupName = 'HSZ 09 - Nappali és éjszakai jelzések - 1 (3. nap)'; break;
                case $origCsvId <= 1057:
                    $groupName = 'HSZ 10 - Különleges jelzések (3. nap)'; break;
                case $origCsvId <= 1088:
                    $groupName = 'HSZ 11 - Hangjelzések, rádió, AIS (4. nap)'; break;
                case $origCsvId <= 1115:
                    $groupName = 'HSZ 12 - Korlátozott látási viszonyok (4. nap)'; break;
                case $origCsvId <= 1142:
                    $groupName = 'HSZ 13 - Veszteglés (4. nap)'; break;
                case $origCsvId <= 1158:
                    $groupName = 'HSZ 14 - Kompok, hajóhíd (4. nap)'; break;
                case $origCsvId <= 1198:
                    $groupName = 'HSZ 15 - Műtárgyak (4. nap)'; break;
                case $origCsvId <= 1238:
                    $groupName = 'HSZ 16 - Tavi jelzések (4. nap)'; break;
                case $origCsvId <= 1278:
                    $groupName = 'HSZ 17 - Nappali és éjszakai jelzések - 2 (4. nap)'; break;
                case $origCsvId <= 1318:
                    $groupName = 'HSZ 18 - Hajózási szabályok - 3 (4. nap)'; break;
                case $origCsvId <= 1369:
                    $groupName = 'HSZ 19 - Hajózási szabályok - 4 (4. nap)'; break;
                case $origCsvId <= 1420:
                    $groupName = 'HSZ 20 - Hajózási szabályok - 5 (4. nap)'; break;
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

            $groupName = '';
            if ($csvFile == '001_vitorlaskishajo.csv') {
                switch ($group) {
                    case 2:
                        $groupName = 'Hajózási ismeretek - Közös vitorlás és kisgéphajós (2. nap)'; break;
                    case 3:
                        $groupName = 'Hajózási ismeretek - Közös vitorlás és kisgéphajós (3. nap)'; break;
                    case 4:
                        $groupName = 'Hajózási ismeretek - Vitorlás kérdések (4. nap)'; break;
                }
            }
            if ($csvFile == '001_kisgephajo.csv') {
                switch ($group) {
                    case 0:
                        $groupName = 'Hajózási ismeretek - Kisgéphajós kérdések, amik már voltak a közös részben'; break;
                    case 4:
                        $groupName = 'Hajózási ismeretek - Kisgéphajós kérdések (4. nap)'; break;
                }
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
