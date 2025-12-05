<?php


use Illuminate\Support\Collection;
use Spatie\Async\Pool;

class ProblemState
{
    public string $inputFile = 'src/day_4/input.txt';
}

require('grid.php');

$log = getLogger();
$state = new ProblemState();


$file = new SplFileObject($state->inputFile, "r");
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

$gridData = [];
while (!$file->eof()) {
    $line = $file->current();

    $gridData[] = str_split($line[0]);

    $file->next();
}


$grid = new Grid($gridData);

$result = 0;
$initResult = null;
while($result !== $initResult) {
    $initResult = $result;

    foreach ($grid->positions() as $position) {
        if ($position === '.') {
            continue;
        }

        if ($grid->getCurrentPositionMatches('@') <= 3) {
            $result++;
            $grid->iterator->updateCurrent('.');
        }
    }
}
dd($result);
