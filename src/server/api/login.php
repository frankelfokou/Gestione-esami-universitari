<?php
require_once dirname(__DIR__) . '/config/DatabasePDO.php';
require_once dirname(__DIR__) . '/config/DatabaseWrapper.php';
require_once dirname(__DIR__) . '/repository/UtenteRepository.php';

function loginEP()
{
    header('Content-Type: application/json');

    // Recupero input JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email e password obbligatorie']);
        return;
    }

    try {
        $db = new DatabaseWrapper((new DatabasePDO())->pdo());
        $utenteRepo = new UtenteRepository($db);

        $user = $utenteRepo->findByEmail($data['email']);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Credenziali non valide']);
            return;
        }

        // Verifica password (si assume l'uso di password_hash di PHP)
        // NOTA DI SICUREZZA: In un ambiente reale, forzare l'uso di password hashate.
        if (password_verify($data['password'], $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['utente_ID'];

            echo json_encode([
                'message' => 'Login effettuato con successo.',
                'user' => [
                    'id' => $user['utente_ID'],
                    'nome' => $user['nome'],
                    'cognome' => $user['cognome']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenziali non valide.']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Errore del server: ' . $e->getMessage()]);
    }
}
