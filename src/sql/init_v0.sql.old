-- init.sql
CREATE TABLE IF NOT EXISTS utenti (
    id SERIAL PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nome VARCHAR(50),
    cognome VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS esami (
    id SERIAL PRIMARY KEY,
    id_utente INTEGER NOT NULL,
    materia VARCHAR(100) NOT NULL,
    voto INTEGER NOT NULL CHECK (voto >= 18 AND voto <= 32),
    cfu INTEGER NOT NULL,
    data_esame DATE,
    FOREIGN KEY (id_utente) REFERENCES utenti(id) ON DELETE CASCADE
);

-- Utente di test
INSERT INTO utenti (email, password_hash, nome, cognome) 
VALUES ('test@unipr.it', 'password_segreta', 'Mario', 'Rossi');