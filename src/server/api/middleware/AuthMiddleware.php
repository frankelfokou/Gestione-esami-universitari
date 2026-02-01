<?php
// src/server/api/middleware/AuthMiddleware.php

class AuthMiddleware
{
    /**
     * Verifica se l'utente è autenticato.
     * Avvia la sessione se non è già stata avviata.
     * 
     * @return int L'ID dell'utente autenticato.
     */
    public static function isAuthenticated(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorizzato']);
            exit;
        }

        return $_SESSION['user_id'];
    }
}
