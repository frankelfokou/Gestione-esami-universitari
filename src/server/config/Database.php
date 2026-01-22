<?php

final class Database
{
    private PDO $pdo;

    public function __construct()
    {
        // Legge env 
        $host = getenv('DB_HOST') ?: 'db';
        $name = getenv('DB_NAME') ?: 'gestione_esami';
        $user = getenv('DB_USER') ?: 'user_esami';
        $pass = getenv('DB_PASS') ?: 'pass_esami';
        $port = getenv('DB_PORT') ?: '5432';

        // DSN Postgres
        $dsn = "pgsql:host={$host};port={$port};dbname={$name}";

        // Crea PDO + opzioni
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}

?>
