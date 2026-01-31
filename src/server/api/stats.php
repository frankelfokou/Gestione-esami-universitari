<?php
require_once dirname(path: __DIR__) . '/config/DatabasePDO.php';
require_once dirname(path: __DIR__) . '/config/DatabaseWrapper.php';
require_once __DIR__ . '/strategies/MediaAritmetica.php';
require_once __DIR__ . '/strategies/MediaPonderata.php';

require_once dirname(path: __DIR__) . '/repository/EsameRepository.php';

function statsEP(): void
{
    header(header: 'Content-Type: application/json');

    session_start();
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }
    $userId = $_SESSION['user_id'];

    try {
        $db = new DatabaseWrapper((new DatabasePDO())->pdo());
        $esameRepo = new EsameRepository($db);

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

        echo json_encode(value: [
            'mediaA' => $mediaA,
            'mediaP' => $mediaP,
            'proiezione' => $proiezione,
            'cfuTotali' => $totCFU
        ]);

    } catch (Exception $e) {
        http_response_code(response_code: 500);
        echo json_encode(value: ['error' => $e->getMessage()]);
    }
}
