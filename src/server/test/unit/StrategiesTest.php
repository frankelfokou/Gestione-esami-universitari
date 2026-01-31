<?php
// src/server/test/unit/StrategiesTest.php

require_once dirname(dirname(__DIR__)) . '/api/strategies/MediaStrategy.php';
require_once dirname(dirname(__DIR__)) . '/api/strategies/MediaAritmetica.php';
require_once dirname(dirname(__DIR__)) . '/api/strategies/MediaPonderata.php';
require_once dirname(dirname(__DIR__)) . '/api/strategies/MediaPrevisionale.php';

function assertEqual($expected, $actual, $message)
{
    if (abs($expected - $actual) < 0.01) {
        echo "PASS: $message\n";
    } else {
        echo "FAIL: $message (Expected: $expected, Got: $actual)\n";
    }
}

echo "Running Strategy Unit Tests...\n\n";

// --- Setup Data ---
$esami = [
    ['voto' => 30, 'cfu' => 6],
    ['voto' => 24, 'cfu' => 9],
    ['voto' => 28, 'cfu' => 6]
];

// --- Test Media Aritmetica ---
// (30 + 24 + 28) / 3 = 82 / 3 = 27.33
$stratA = new MediaAritmetica();
$resA = $stratA->calcola($esami);
assertEqual(27.33, $resA, "Media Aritmetica should be ~27.33");

// --- Test Media Ponderata ---
// (30*6 + 24*9 + 28*6) / (6+9+6) 
// (180 + 216 + 168) / 21 = 564 / 21 = 26.857
$stratP = new MediaPonderata();
$resP = $stratP->calcola($esami);
assertEqual(26.86, $resP, "Media Ponderata should be ~26.86");

// --- Test Media Previsionale ---
// Current Ponderata: 26.86
// Acquired CFU: 21
// Total CFU: 180
// Target: 110 (requires avg 30) -> Target Avg = 30
// Formula: AvgFuture = ((30 * 180) - (26.86 * 21)) / (180 - 21)
// AvgFuture = (5400 - 564.06) / 159 = 4835.94 / 159 = 30.41
$stratPrev = new MediaPrevisionale();
$resPrev = $stratPrev->calcola(26.86, 21, 180, 110);
// Note: It's > 30, so effectively "impossible" but mathematically correct
assertEqual(30.41, $resPrev, "Forecast for 110 should be ~30.41");

echo "\nTests Completed.\n";
