<?php
// src/server/api/strategies/DistribuzioneVotiStrategy.php

class DistribuzioneVotiStrategy
{

    /**
     * Calculates the frequency distribution of grades.
     * Buckets: 18-21, 22-24, 25-27, 28-29, 30, 30L
     * 
     * @param array $esami Array of exams.
     * @return array Distribution dictionary.
     */
    public function calcola(array $esami): array
    {
        $distribuzione = [
            '18-21' => 0,
            '22-24' => 0,
            '25-27' => 0,
            '28-29' => 0,
            '30' => 0,
            '30L' => 0
        ];

        foreach ($esami as $esame) {
            $voto = intval($esame['voto']);
            $lode = isset($esame['lode']) && $esame['lode'] == 1;

            if ($voto == 30 && $lode) {
                $distribuzione['30L']++;
            } elseif ($voto == 30) {
                $distribuzione['30']++;
            } elseif ($voto >= 28) {
                $distribuzione['28-29']++;
            } elseif ($voto >= 25) {
                $distribuzione['25-27']++;
            } elseif ($voto >= 22) {
                $distribuzione['22-24']++;
            } elseif ($voto >= 18) {
                $distribuzione['18-21']++;
            }
        }

        return $distribuzione;
    }
}
