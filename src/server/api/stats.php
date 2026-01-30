<?php
require_once dirname(__DIR__) . '/config/DatabasePDO.php';
require_once dirname(__DIR__) . '/config/DatabaseWrapper.php';
require_once __DIR__ . '/strategies/MediaAritmetica.php';
require_once __DIR__ . '/strategies/MediaPonderata.php';

require_once dirname(__DIR__) . '/repository/EsameRepository.php';

function statsEP()
{
    header('Content-Type: application/json');
    try {
        $db = new DatabaseWrapper((new DatabasePDO())->pdo());
        $esameRepo = new EsameRepository($db);

        // SECURITY SIMULATION: Assuming user ID 1 is logged in
        $userId = 1;

        // Use repository to find exams for this student
        $esami = $esameRepo->findByStudent($userId);

        $mediaAritmeticaStrategy = new MediaAritmetica();
        $mediaPonderataStrategy = new MediaPonderata();

        $mediaA = $mediaAritmeticaStrategy->calcola($esami);
        $mediaP = $mediaPonderataStrategy->calcola($esami);

        $totCFU = 0;
        foreach ($esami as $e) {
            $totCFU += $e['cfu'];
        }

        // Proiezione voto di laurea: (Media Ponderata * 110) / 30
        $proiezione = ($mediaP * 110) / 30;

        echo json_encode([
            'mediaA' => $mediaA,
            'mediaP' => $mediaP,
            'proiezione' => $proiezione,
            'cfuTotali' => $totCFU
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
