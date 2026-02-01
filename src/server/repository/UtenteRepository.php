<?php
// src/server/repository/UtenteRepository.php

final class UtenteRepository
{
    public function __construct(private DatabaseWrapper $db)
    {
    }


    public function create(array $data): int
    {
        $this->db->execute(
            'INSERT INTO utente (nome, cognome, email, password) VALUES (:nome, :cognome, :email, :password)',
            [
                'nome' => $data['nome'],
                'cognome' => $data['cognome'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]
        );

        return (int) $this->db->lastInsertId();
    }

    public function findAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM applicazione.utente ORDER BY utente_id");
    }

    public function findById(int $utenteId): ?array
    {
        return $this->db->fetchOne(
            'SELECT * FROM applicazione.utente WHERE utente_id = :id',
            ['id' => $utenteId]
        );
    }

    /**
     * Trova un utente tramite la sua email.
     * Metodo aggiunto per supportare il processo di login.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->db->fetchOne(
            'SELECT * FROM applicazione.utente WHERE email = :email',
            ['email' => $email]
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            'UPDATE applicazione.utente SET nome = :nome, cognome = :cognome, email = :email, password = :password WHERE utente_id = :id',
            [
                'id' => $id,
                'nome' => $data['nome'],
                'cognome' => $data['cognome'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]
        );
    }

    public function delete(int $utenteId): void
    {
        $stmt = $this->db->prepare("DELETE FROM applicazione.utente WHERE utente_id = :id");
        $stmt->execute(['id' => $utenteId]);
    }


}

