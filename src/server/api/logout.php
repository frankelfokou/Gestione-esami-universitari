<?php
// src/server/api/logout.php

function logoutEP()
{
    header('Content-Type: application/json');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Svuota tutti i valori della sessione
    $_SESSION = array();

    // Distruggi il cookie di sessione
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Distruggi la sessione
    session_destroy();

    echo json_encode(['message' => 'Logout effettuato con successo']);
}
