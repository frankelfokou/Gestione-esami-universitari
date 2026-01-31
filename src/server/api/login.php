<?php
require_once dirname(__DIR__) . '/config/DatabasePDO.php';
require_once dirname(__DIR__) . '/config/DatabaseWrapper.php';
require_once dirname(__DIR__) . '/repository/UtenteRepository.php';

function loginEP()
{
    header('Content-Type: application/json');

    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password required']);
        return;
    }

    try {
        $db = new DatabaseWrapper((new DatabasePDO())->pdo());
        $utenteRepo = new UtenteRepository($db);

        $user = $utenteRepo->findByEmail($data['email']);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        // Verify password (assuming standard PHP password_hash was used)
        // If passwords in DB are plain text (which is bad but possible in legacy/demo apps), 
        // we might need to check that. For now, assuming password_verify.
        // SECURITY NOTE: In a real environment, force upgrade to hashed passwords.
        if (password_verify($data['password'], $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['utente_ID'];

            echo json_encode([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['utente_ID'],
                    'nome' => $user['nome'],
                    'cognome' => $user['cognome']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
