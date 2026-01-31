<?php
// src/server/api/strategies/TrendMedieStrategy.php

class TrendMedieStrategy
{

    /**
     * Calculates the evolution of the weighted average over time.
     * Assumes $esami is ordered by date or has a date field.
     * 
     * @param array $esami Array of exams.
     * @return array Array of points ['data' => date, 'media' => float]
     */
    public function calcola(array $esami): array
    {
        // Sort exams by date just in case
        usort($esami, function ($a, $b) {
            return strcmp($a['data'], $b['data']);
        });

        $trend = [];
        $sommaPonderata = 0;
        $totCFU = 0;

        foreach ($esami as $esame) {
            $voto = $esame['voto'];
            $cfu = $esame['cfu'];

            // Skip "approved" exams without grade if any (usually represented as 0 or null in some systems)
            // Assuming simplified model where voto is always valid number for calculation
            if ($voto < 18)
                continue;

            $sommaPonderata += ($voto * $cfu);
            $totCFU += $cfu;

            $mediaCorrente = $totCFU > 0 ? $sommaPonderata / $totCFU : 0;

            $trend[] = [
                'data' => $esame['data'],
                'esame' => $esame['nome'], // Adding exam name for context
                'media_progressiva' => round($mediaCorrente, 2)
            ];
        }

        return $trend;
    }
}
