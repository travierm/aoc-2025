<?php

use Illuminate\Support\Collection;
use Spatie\Async\Pool;

$inputFile = 'src/day_5/input.txt';

$log = getLogger();


$file = new SplFileObject($inputFile, "r");
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);

$ranges = collect();
$ingredients = collect();

while (!$file->eof()) {
    $line = $file->current()[0];

    if(str_contains($line, '-')) {
        $ranges->push($line);
    }else{
        $ingredients->push((int) $line);
    }

    $file->next();
}


$rangeAssertions = $ranges->map(function($r) {
    $rangeInts = explode('-', $r);

    $start = (int) $rangeInts[0];
    $end = (int) $rangeInts[1];

    return function(int $i) use($start, $end)  {
        return $i >= $start && $i <= $end;
    };
});

$count = 0;
$ingredients->each(function($t) use($rangeAssertions, &$count) {
    foreach($rangeAssertions as $assertion) {
        if($assertion($t)) {
            $count++;
            break;
        }
    }
});

dd($count);
