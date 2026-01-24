<?php
// src/server/repository/UtenteRepository.php

final class UtenteRepository
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utente ORDER BY utente_id");
        return $stmt->fetchAll();
    }

    public function findById(int $utenteId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utente WHERE utente_id = :id");
        $stmt->execute(['id' => $utenteId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Metti qui le colonne reali che hai in tabella (per ora esempio)
    public function create(string $nome, string $cognome, date $data_di_nascita, string $email): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utente (nome, cognome, email)
            VALUES (:n, :c, :e)
            RETURNING utente_id
        ");
        $stmt->execute(['n' => $nome, 'c' => $cognome, 'e' => $email]);

        return (int)$stmt->fetchColumn();
    }

    public function delete(int $utenteId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM utente WHERE utente_id = :id");
        $stmt->execute(['id' => $utenteId]);
    }
}

