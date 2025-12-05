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


$finalGrid = [];
$result = 0;
foreach ($grid->positions() as $position) {
    $finalGridValue = null;
    if($position === '.') {
        $finalGridValue = '.';
        $finalGrid[$grid->iterator->key()->x][$grid->iterator->key()->y] = $finalGridValue;

        continue;
    }

    if($grid->getCurrentPositionMatches('@') <= 3) {
        $finalGridValue = 'X';
        $result++;
    }else{
        $finalGridValue = '@';
    }

    $finalGrid[$grid->iterator->key()->x][$grid->iterator->key()->y] = $finalGridValue;
}

dump($finalGrid);
dd($result);
