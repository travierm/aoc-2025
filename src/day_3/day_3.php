<?php

use Illuminate\Support\Collection;
use Spatie\Async\Pool;

class ProblemState
{
    public string $inputFile = 'src/day_3/input.txt';
}

$log = getLogger();
$state = new ProblemState();

$file = new SplFileObject($state->inputFile, "r");
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

$banks = [];
while (!$file->eof()) {
    $line = $file->current();
    $banks[] = str_split($line[0]);
    $file->next();
}

$joltages  = collect();

// find biggest number
// find the highest number after it
// if its the last number, find the largest number in front of it
foreach($banks as $bank) {
    $bankCopy = $bank;
    arsort($bank);

    $largestRow = array_slice($bank, 0, 1, true);
    $largestRowIndex = array_key_first($largestRow);
    $largestRowValue = $largestRow[$largestRowIndex];
    $largestRowIsOnLastIndex = $largestRowIndex === count($bank) - 1;

    if(!$largestRowIsOnLastIndex) {
        $remainingRecords = array_slice($bankCopy, array_search($largestRowIndex, array_keys($bankCopy)) + 1, null, true);
        arsort($remainingRecords);

        $remainingDigit = array_shift($remainingRecords);
        $joltages->push($largestRowValue . $remainingDigit);
    }else{
        $copyOfCopy = $bankCopy;
        unset($copyOfCopy[$largestRowIndex]);
        arsort($copyOfCopy);

        $remainingDigit = array_shift($copyOfCopy);
        $joltages->push($remainingDigit . $largestRowValue);
    }
}

dd($joltages->sum());
