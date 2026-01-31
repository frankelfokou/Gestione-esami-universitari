<?php
require_once dirname(__DIR__) . '/config/DatabasePDO.php';
require_once dirname(__DIR__) . '/config/DatabaseWrapper.php';
require_once __DIR__ . '/strategies/MediaAritmetica.php';
require_once __DIR__ . '/strategies/MediaPonderata.php';
require_once __DIR__ . '/strategies/MediaPrevisionale.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once dirname(__DIR__) . '/repository/EsameRepository.php';

function statsEP(): void
{
    header(header: 'Content-Type: application/json');

    // Authentication via Middleware
    $userId = AuthMiddleware::isAuthenticated();

    try {
        $db = new DatabaseWrapper((new DatabasePDO())->pdo());
        $esameRepo = new EsameRepository($db);

        // Use repository to find exams for this student
        $esami = $esameRepo->findByStudent($userId);

        $mediaAritmeticaStrategy = new MediaAritmetica();
        $mediaPonderataStrategy = new MediaPonderata();
        $mediaPrevisionaleStrategy = new MediaPrevisionale();

        $mediaA = $mediaAritmeticaStrategy->calcola($esami);
        $mediaP = $mediaPonderataStrategy->calcola($esami);

        $totCFU = 0;
        foreach ($esami as $e) {
            $totCFU += $e['cfu'];
        }

        // Proiezione voto di laurea: (Media Ponderata * 110) / 30
        $proiezione = ($mediaP * 110) / 30;

        // Forecast: Goal 110L (or just 110)
        // Assuming Bachelor degree (180 CFU total)
        $cfuTotaliCorso = 180;
        $mediaFutura = $mediaPrevisionaleStrategy->calcola($mediaP, $totCFU, $cfuTotaliCorso, 110);

        echo json_encode(value: [
            'mediaA' => $mediaA,
            'mediaP' => $mediaP,
            'proiezione' => $proiezione,
            'cfuTotali' => $totCFU,
            'previsione110' => $mediaFutura
        ]);

    } catch (Exception $e) {
        http_response_code(response_code: 500);
        echo json_encode(value: ['error' => $e->getMessage()]);
    }
}
