<?php
// src/server/api/middleware/AuthMiddleware.php

class AuthMiddleware
{
    /**
     * Verifies if the user is authenticated.
     * Starts the session if not already started.
     * 
     * @return int The authenticated user ID.
     */
    public static function isAuthenticated(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        return $_SESSION['user_id'];
    }
}
