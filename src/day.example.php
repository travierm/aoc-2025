<?php
class ProblemState
{
    public string $inputFile = 'src/day_1/input.txt';

}

$log = getLogger();
$state = new ProblemState();


$file = new SplFileObject($state->inputFile, 'r');

while (!$file->eof()) {
    $line = $file->current();

    $file->next();
}