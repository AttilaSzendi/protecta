<?php

const SECOND_SMALLEST_INDEX = 1;

function calculateBiggestRadius(array $points): float
{
    $groups = [];

    foreach ($points as $point) {
        $groups[$point['id']][] = sqrt($point['x'] ** 2 + $point['y'] ** 2);
    }

    $secondSmallestDistancesPerGroup = [];

    foreach ($groups as $distances) {
        if (count($distances) < 2) {
            continue;
        }

        sort($distances);

        $secondSmallestDistancesPerGroup[] = $distances[SECOND_SMALLEST_INDEX];
    }

    return min($secondSmallestDistancesPerGroup);
}

echo calculateBiggestRadius([
    [
        'id' => 'C',
        'x'  => 15,
        'y'  => 15,
    ],
    [
        'id' => 'C',
        'x'  => 2,
        'y'  => 150,
    ],
    [
        'id' => 'A',
        'x'  => 10,
        'y'  => 10,
    ],
    [
        'id' => 'A',
        'x'  => 5,
        'y'  => 5,
    ],
    [
        'id' => 'A',
        'x'  => 30,
        'y'  => 30,
    ],
    [
        'id' => 'B',
        'x'  => 15,
        'y'  => 15,
    ],
]);

function dd($var)
{
    var_dump($var);
    die();
}

