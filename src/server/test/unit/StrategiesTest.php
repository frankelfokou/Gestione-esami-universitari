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
        echo "FAIL: $message (Atteso: $expected, Ottenuto: $actual)\n";
    }
}

echo "Esecuzione Unit Test delle Strategie...\n\n";

// Setup Data
$esami = [
    ['voto' => 30, 'cfu' => 6],
    ['voto' => 24, 'cfu' => 9],
    ['voto' => 28, 'cfu' => 6]
];

// Test Media Aritmetica: (30+24+28)/3 = 27.33
$stratA = new MediaAritmetica();
$resA = $stratA->calcola($esami);
assertEqual(27.33, $resA, "La Media Aritmetica dovrebbe essere ~27.33");

// Test Media Ponderata: (30*6 + 24*9 + 28*6) / (6+9+6) = 564/21 = 26.86
$stratP = new MediaPonderata();
$resP = $stratP->calcola($esami);
assertEqual(26.86, $resP, "La Media Ponderata dovrebbe essere ~26.86");

// Test Media Previsionale
// Media attuale: 26.86, CFU fatti: 21, Target: 110
// TargetMedia = (110*30)/110 = 30
// MediaFutura = (30*180 - 26.86*21) / (180-21) = (5400-564.06)/159 = 30.41
$stratPrev = new MediaPrevisionale();
$resPrev = $stratPrev->calcola(26.86, 21, 180, 110);
assertEqual(30.41, $resPrev, "La previsione per il 110 dovrebbe essere ~30.41");

// Test Distribuzione Voti
require_once dirname(dirname(__DIR__)) . '/api/strategies/DistribuzioneVotiStrategy.php';
$stratDist = new DistribuzioneVotiStrategy();
$resDist = $stratDist->calcola($esami);
assertEqual(1, $resDist['28-29'], "Dovrebbe esserci 1 esame nella fascia 28-29");
assertEqual(1, $resDist['22-24'], "Dovrebbe esserci 1 esame nella fascia 22-24");
assertEqual(1, $resDist['30'], "Dovrebbe esserci 1 esame con voto 30");

// Test Trend
$esamiTrend = [
    ['voto' => 24, 'cfu' => 6, 'data' => '2023-01-01', 'nome' => 'A'],
    ['voto' => 28, 'cfu' => 6, 'data' => '2023-06-01', 'nome' => 'B']
];
// 1st point: 24*6/6 = 24
// 2nd point: (24*6 + 28*6)/12 = 312/12 = 26
require_once dirname(dirname(__DIR__)) . '/api/strategies/TrendMedieStrategy.php';
$stratTrend = new TrendMedieStrategy();
$resTrend = $stratTrend->calcola($esamiTrend);

assertEqual(24.0, $resTrend[0]['media_progressiva'], "La media del primo punto del trend dovrebbe essere 24");
assertEqual(26.0, $resTrend[1]['media_progressiva'], "La media del secondo punto del trend dovrebbe essere 26");

echo "\nTest Completati.\n";
