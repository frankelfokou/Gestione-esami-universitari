<?php
require_once __DIR__ . '/MediaStrategy.php';

class MediaAritmetica implements MediaStrategy
{
    public function calcola(array $esami): float
    {
        if (empty($esami)) {
            return 0;
        }

        $sommaVoti = 0;
        foreach ($esami as $esame) {
            $sommaVoti += $esame['voto'];
        }

        return $sommaVoti / count($esami);
    }
}
