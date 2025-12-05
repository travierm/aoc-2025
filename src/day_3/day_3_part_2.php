<?php

use Illuminate\Support\Collection;
use Spatie\Async\Pool;

class ProblemState
{
    public string $inputFile = 'src/day_2/input.txt';
    public Collection $ids;
    public Collection $invalidIDs;

    public function __construct()
    {
        $this->ids = collect();
        $this->invalidIDs = collect();
    }
}

$log = getLogger();
$state = new ProblemState();

$contents = trim(preg_replace('/\r\n|\r|\n/', ' ',file_get_contents($state->inputFile)));
$rows = collect(explode(',', $contents));




$bench = new \App\Lib\Benchmark($log, 1);

require_once('lib.php');

$bench->bench('process_rows', function() use($rows, $log, $state) {
    foreach($rows as $row) {
        $state->invalidIDs->push(...processRowPart2($row));
    }
});

dump($state->invalidIDs);
dd($state->invalidIDs->sum());