<?php
require_once __DIR__ . '/MediaStrategy.php';

class MediaPonderata implements MediaStrategy
{
    public function calcola(array $esami): float
    {
        if (empty($esami)) {
            return 0;
        }

        $sommaPonderata = 0;
        $totCFU = 0;

        foreach ($esami as $esame) {
            $sommaPonderata += ($esame['voto'] * $esame['cfu']);
            $totCFU += $esame['cfu'];
        }

        return $totCFU > 0 ? $sommaPonderata / $totCFU : 0;
    }
}
