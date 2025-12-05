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

$bench->bench('process_rows', function() use($rows, $log, $state) {
    $pool = Pool::create()
        ->concurrency(16);

    foreach($rows as $row) {
        $pool->add(function () use ($row) {
            require_once(__DIR__ . '/lib.php');
            return processRow($row);
        })->then(function(array $ints) use($state) {
            $state->invalidIDs->push(...$ints);
        })->catch(function (Throwable $th) use($log) {
            $log->error($th->getMessage());
        });
    }

    $pool->wait();
});


dd($state->invalidIDs->sum());