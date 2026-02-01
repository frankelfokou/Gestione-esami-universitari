<?php
// src/server/api/strategies/TrendMedieStrategy.php

class TrendMedieStrategy
{

    /**
     * Calcola l'evoluzione della media ponderata nel tempo.
     * Assume che $esami sia ordinato per data o abbia un campo data.
     * 
     * @param array $esami Array di esami.
     * @return array Array di punti ['data' => data, 'media' => float]
     */
    public function calcola(array $esami): array
    {
        // Ordina gli esami per data
        usort($esami, function ($a, $b) {
            return strcmp($a['data'], $b['data']);
        });

        $trend = [];
        $sommaPonderata = 0;
        $totCFU = 0;

        foreach ($esami as $esame) {
            $voto = $esame['voto'];
            $cfu = $esame['cfu'];

            // Salta gli esami approvati senza voto se presenti
            if ($voto < 18)
                continue;

            $sommaPonderata += ($voto * $cfu);
            $totCFU += $cfu;

            $mediaCorrente = $totCFU > 0 ? $sommaPonderata / $totCFU : 0;

            $trend[] = [
                'data' => $esame['data'],
                'esame' => $esame['nome'], // Aggiunta nome esame per contesto
                'media_progressiva' => round($mediaCorrente, 2)
            ];
        }

        return $trend;
    }
}
