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

/**
 * Recupera e calcola tutti i dati statistici per un determinato utente.
 * Questa funzione è progettata per essere compatibile con ResponseInterface::setData().
 */
function getStatsData(int $userId): array
{
    $db = new DatabaseWrapper((new DatabasePDO())->pdo());
    $esameRepo = new EsameRepository($db);

    // Recupero esami tramite repository (filtro multi-utenza)
    $esami = $esameRepo->findByStudent($userId);

    // Inizializzazione strategie
    $distribuzioneStrategy = new DistribuzioneVotiStrategy();
    $trendStrategy = new TrendMedieStrategy();
    $mediaAritmeticaStrategy = new MediaAritmetica();
    $mediaPonderataStrategy = new MediaPonderata();
    $mediaPrevisionaleStrategy = new MediaPrevisionale();

    // Esecuzione calcoli
    $mediaA = $mediaAritmeticaStrategy->calcola($esami);
    $mediaP = $mediaPonderataStrategy->calcola($esami);
    $distribuzione = $distribuzioneStrategy->calcola($esami);
    $trend = $trendStrategy->calcola($esami);

    $totCFU = 0;
    foreach ($esami as $e) {
        $totCFU += $e['cfu'];
    }

    // Proiezione voto di laurea
    $proiezione = ($mediaP * 110) / 30;

    // Previsione: Obiettivo 110
    $cfuTotaliCorso = 180;
    $mediaFutura = $mediaPrevisionaleStrategy->calcola($mediaP, $totCFU, $cfuTotaliCorso, 110);

    return [
        'mediaA' => round($mediaA, 2),
        'mediaP' => round($mediaP, 2),
        'proiezione' => round($proiezione, 2),
        'cfuTotali' => $totCFU,
        'previsione110' => $mediaFutura,
        'distribuzioneVoti' => $distribuzione,
        'trendMedia' => $trend
    ];
}

/**
 * Controller per l'endpoint delle statistiche.
 */
function statsEP(): void
{
    header('Content-Type: application/json');

    $userId = AuthMiddleware::isAuthenticated();

    try {
        $data = getStatsData($userId);
        echo json_encode($data);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Errore del server: ' . $e->getMessage()]);
    }
}
