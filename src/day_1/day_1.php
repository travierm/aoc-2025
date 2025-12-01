<?php

use Illuminate\Support\Collection;

enum Direction
{
    CASE LEFT;
    case RIGHT;
}


class ProblemState
{
    public string $inputFile = "src/day_1/example.txt";
    public Collection $rotations;
    public int $currentPointer = 50;
    public int $timesOnZero = 0;

    public function __construct() {
        $this->rotations = collect();
    }

    public function middleware(int $pointer, Direction $lastDirection, int $lastAmount)
    {
        if($pointer === 0) {
            $this->timesOnZero += 1;
        }
    }
}

$log = getLogger();
$state = new ProblemState();

$file = new SplFileObject($state->inputFile, "r");
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

while (!$file->eof()) {
    $line = $file->current();
    $state->rotations->push($line[0]);
    $file->next();
}

foreach($state->rotations as $rotationRaw)
{
    $direction = str_starts_with($rotationRaw, 'L') ? Direction::LEFT : Direction::RIGHT;
    $amount = (int) substr($rotationRaw, 1);

    if($direction === Direction::RIGHT) {
        $state->currentPointer += $amount;
    }else{
        $state->currentPointer -= $amount;
    }

    while($state->currentPointer > 99 || $state->currentPointer < 0) {
        $pointerValue = $state->currentPointer;

        if ($state->currentPointer > 99) {
            $pointerValue = $pointerValue - 99;

            $state->currentPointer = $pointerValue;
        }else{
            $pointerValue = 99 - abs($pointerValue + 1);

            $state->currentPointer = $pointerValue;
        }
    }


    dump($state->currentPointer);
    $state->middleware($state->currentPointer, $direction, $amount);
}

dd($state->timesOnZero);