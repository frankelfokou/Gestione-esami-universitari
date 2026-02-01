<?php
// src/server/api/strategies/MediaPrevisionale.php

class MediaPrevisionale
{

    /**
     * Calcola la media dei voti necessaria negli esami futuri per raggiungere un voto di laurea target.
     * 
     * @param float $mediaPonderataCurrent Media ponderata attuale.
     * @param int $cfuAcquisiti Totale CFU acquisiti finora.
     * @param int $cfuTotali Totale CFU richiesti per la laurea (es. 180 per la Triennale).
     * @param int $targetVotoLaurea Voto di laurea obiettivo (es. 110).
     * @return float Media necessaria per i restanti esami.
     */
    public function calcola(float $mediaPonderataCurrent, int $cfuAcquisiti, int $cfuTotali = 180, int $targetVotoLaurea = 110): float
    {
        // Voto Laurea = (Media Ponderata Finale * 110) / 30
        // Media Ponderata Finale = (Target * 30) / 110

        $targetMediaFinale = ($targetVotoLaurea * 30) / 110;

        $cfuMancanti = $cfuTotali - $cfuAcquisiti;

        if ($cfuMancanti <= 0) {
            return 0; // Corso completato
        }

        // Formula: MediaFutura = ((MediaFinale * CfuTotali) - (MediaCorrente * CfuFatti)) / CfuMancanti

        $sommaPonderataAttuale = $mediaPonderataCurrent * $cfuAcquisiti;
        $sommaPonderataTarget = $targetMediaFinale * $cfuTotali;

        $mediaFuturaNecessaria = ($sommaPonderataTarget - $sommaPonderataAttuale) / $cfuMancanti;

        return $mediaFuturaNecessaria;
    }
}
