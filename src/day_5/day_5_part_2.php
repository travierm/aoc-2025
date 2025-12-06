<?php

use Illuminate\Support\Collection;
use Spatie\Async\Pool;

$inputFile = 'src/day_5/input.txt';

$log = getLogger();


$file = new SplFileObject($inputFile, "r");
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);

$ranges = [];

while (!$file->eof()) {
    $line = $file->current()[0];

    if (str_contains($line, '-')) {
        $ranges[] = $line;
    }

    $file->next();
}

function getRangeInts(string $range)
{
    list($start, $end) = explode('-', $range);

    return [(int)$start, (int)$end];
}

usort($ranges, function ($a, $b) {
    list($startA, $endA) = explode('-', $a);
    list($startB, $endB) = explode('-', $b);

    $startCompare = (int)$startA - (int)$startB;

    if ($startCompare !== 0) {
        return $startCompare;
    }

    return (int)$endA - (int)$endB;
});


$compacted = [];

$rangesCopy = $ranges;

while (count($ranges)) {
    $currentRange = array_shift($ranges);
    $currentRangeInts = getRangeInts($currentRange);

    $rangeStart = $currentRangeInts[0];
    $rangeEnd = $currentRangeInts[1];

    $log->info($currentRange);

    $canCompact = true;
    while ($canCompact && count($ranges)) {
        $nextRange = $ranges[0];
        $nextRangeInts = getRangeInts($nextRange);

        if ($rangeEnd >= $nextRangeInts[0]) {
            // remove the next range since we're compacting it
            array_shift($ranges);
            $rangeEnd = $nextRangeInts[1];

            $log->info($currentRange . ' can compact ' . $nextRange);
        } else {
            $canCompact = false;

            $log->info($rangeStart . '-' . $rangeEnd . ' stopped compacting');
        }
    }

    $compacted[] = $rangeStart . '-' . $rangeEnd;
}

dd($compacted);


$count = 0;
foreach ($compacted as $compactRange) {
    $rangeSplit = getRangeInts($compactRange);

    if ($rangeSplit[0] === $rangeSplit[1]) {
        $count++;
    } else {
        $count += ($rangeSplit[1] - $rangeSplit[0]) + 1;
    }
}

dump($count);

