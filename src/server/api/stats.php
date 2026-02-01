<?php
require_once dirname(__DIR__) . '/config/DatabasePDO.php';
require_once dirname(__DIR__) . '/config/DatabaseWrapper.php';
require_once __DIR__ . '/strategies/MediaAritmetica.php';
require_once __DIR__ . '/strategies/MediaPonderata.php';
require_once __DIR__ . '/strategies/MediaPrevisionale.php';
require_once __DIR__ . '/strategies/DistribuzioneVotiStrategy.php';
require_once __DIR__ . '/strategies/TrendMedieStrategy.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once dirname(__DIR__) . '/repository/EsameRepository.php';

function statsEP(): void
{
    header(header: 'Content-Type: application/json');

    // Autenticazione tramite Middleware
    $userId = AuthMiddleware::isAuthenticated();

    try {
        $db = new DatabaseWrapper((new DatabasePDO())->pdo());
        $esameRepo = new EsameRepository($db);

        // Uso del repository per trovare gli esami di questo studente
        $esami = $esameRepo->findByStudent($userId);

        $distribuzioneStrategy = new DistribuzioneVotiStrategy();
        $trendStrategy = new TrendMedieStrategy();

        $mediaAritmeticaStrategy = new MediaAritmetica();
        $mediaPonderataStrategy = new MediaPonderata();
        $mediaPrevisionaleStrategy = new MediaPrevisionale();

        $mediaA = $mediaAritmeticaStrategy->calcola($esami);
        $mediaP = $mediaPonderataStrategy->calcola($esami);
        $distribuzione = $distribuzioneStrategy->calcola($esami);
        $trend = $trendStrategy->calcola($esami);

        $totCFU = 0;
        foreach ($esami as $e) {
            $totCFU += $e['cfu'];
        }

        // Proiezione voto di laurea: (Media Ponderata * 110) / 30
        $proiezione = ($mediaP * 110) / 30;

        // Previsione: Obiettivo 110
        // Assumendo una laurea triennale (180 CFU totali)
        $cfuTotaliCorso = 180;
        $mediaFutura = $mediaPrevisionaleStrategy->calcola($mediaP, $totCFU, $cfuTotaliCorso, 110);

        echo json_encode([
            'mediaA' => $mediaA,
            'mediaP' => $mediaP,
            'proiezione' => $proiezione,
            'cfuTotali' => $totCFU,
            'previsione110' => $mediaFutura,
            'distribuzioneVoti' => $distribuzione,
            'trendMedia' => $trend
        ]);

    } catch (Exception $e) {
        http_response_code(response_code: 500);
        echo json_encode(value: ['error' => 'Errore del server: ' . $e->getMessage()]);
    }
}
