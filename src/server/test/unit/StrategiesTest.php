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
assertEqual(30.41, $resPrev, "Forecast for 110 should be ~30.41");

// --- Test Distribuzione Voti ---
// 30, 24, 28
require_once dirname(dirname(__DIR__)) . '/api/strategies/DistribuzioneVotiStrategy.php';
$stratDist = new DistribuzioneVotiStrategy();
$resDist = $stratDist->calcola($esami);
assertEqual(1, $resDist['28-29'], "Should have 1 exam in 28-29 range");
assertEqual(1, $resDist['22-24'], "Should have 1 exam in 22-24 range");
assertEqual(1, $resDist['30'], "Should have 1 exam with 30");

// --- Test Trend ---
// Data needs to be added to mock
$esamiTrend = [
    ['voto' => 24, 'cfu' => 6, 'data' => '2023-01-01', 'nome' => 'A'],
    ['voto' => 28, 'cfu' => 6, 'data' => '2023-06-01', 'nome' => 'B']
];
// 1st point: 24 (avg 24)
// 2nd point: (24*6 + 28*6)/12 = 52/2 = 26
require_once dirname(dirname(__DIR__)) . '/api/strategies/TrendMedieStrategy.php';
$stratTrend = new TrendMedieStrategy();
$resTrend = $stratTrend->calcola($esamiTrend);

assertEqual(24.0, $resTrend[0]['media_progressiva'], "First trend point avg should be 24");
assertEqual(26.0, $resTrend[1]['media_progressiva'], "Second trend point avg should be 26");

echo "\nTests Completed.\n";
