<?php
// src/server/api/strategies/MediaPrevisionale.php

class MediaPrevisionale
{

    /**
     * Calculates the average grade required in future exams to achieve a target graduation grade.
     * 
     * @param float $mediaPonderataCurrent Current weighted average.
     * @param int $cfuAcquisiti Total CFU acquired so far.
     * @param int $cfuTotali Total CFU required for graduation (e.g., 180 for Bachelor).
     * @param int $targetVotoLaurea Target graduation grade (e.g., 110).
     * @return float Required average for remaining exams. Returns -1 if impossible (e.g., required > 30).
     */
    public function calcola(float $mediaPonderataCurrent, int $cfuAcquisiti, int $cfuTotali = 180, int $targetVotoLaurea = 110): float
    {
        // Voto Laurea = (Media Ponderata Finale * 110) / 30
        // Media Ponderata Finale = (Target * 30) / 110

        $targetMediaFinale = ($targetVotoLaurea * 30) / 110;

        $cfuMancanti = $cfuTotali - $cfuAcquisiti;

        if ($cfuMancanti <= 0) {
            return 0; // Course completed
        }

        // Formula: MediaFinale = ((MediaCorrente * CfuFatti) + (MediaFutura * CfuMancanti)) / CfuTotali
        // MediaFutura = ((MediaFinale * CfuTotali) - (MediaCorrente * CfuFatti)) / CfuMancanti

        $sommaPonderataAttuale = $mediaPonderataCurrent * $cfuAcquisiti;
        $sommaPonderataTarget = $targetMediaFinale * $cfuTotali;

        $mediaFuturaNecessaria = ($sommaPonderataTarget - $sommaPonderataAttuale) / $cfuMancanti;

        return $mediaFuturaNecessaria;
    }
}
