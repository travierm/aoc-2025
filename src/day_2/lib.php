<?php
function processRow(string $row)
{
    $rangePoles = explode('-', $row);
    $range = range((int) $rangePoles[0], (int) $rangePoles[1]);

    $results = [];
    foreach($range as $num) {
        if(isMirrorNumber($num)) {
            $results[] = $num;
        }
    }

    return $results;
}

function processRowPart2(string $row)
{
    $rangePoles = explode('-', $row);
    $range = range((int) $rangePoles[0], (int) $rangePoles[1]);

    $results = [];
    foreach($range as $num) {
        if(isMirrorNumber($num)) {
            $results[] = $num;
            continue;
        }

        if(containsSameDigits($num)) {
            $results[] = $num;
        }
    }

    return $results;
}

function containsSameDigits(string $num)
{
    $split = str_split($num);
    $len = count($split);
    $uniqueCount = count(array_unique($split));

    if($uniqueCount === $len) {
        return false;
    }

    $times = $len / $uniqueCount;

    $chunks =  array_chunk($split, ceil(count($split) / $times));
    $chunksConcat = array_map(fn($t) => implode('', $t), $chunks);

    return count(array_unique($chunksConcat)) === 1;
}


function isMirrorNumber(string $id): bool
{
    $len = strlen($id);

    if($len % 2 !== 0) {
        return false;
    }

    $half = $len / 2;
    $left  = substr($id, 0, $half);
    $right = substr($id, $half);

    return $right === $left;
}