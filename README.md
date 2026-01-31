# Documentazione Progetto: Gestione Esami (Modulo Statistiche & Sicurezza)

Questo documento dettaglia l'implementazione delle nuove funzionalità relative al calcolo delle statistiche, alla gestione della sicurezza multi-utente e alle scelte architetturali adottate.

## 1. Funzionalità Implementate

### 1.1 Calcolo Statistiche (`/metrics`)
Il sistema ora fornisce un endpoint per calcolare automaticamente:
*   **Media Aritmetica**: La media semplice dei voti (somma voti / numero esami).
*   **Media Ponderata**: La media pesata sui Crediti Formativi Universitari (CFU).
*   **Proiezione Voto di Laurea**: Stima basata sulla media ponderata `(Media Ponderata * 110) / 30`.
*   **Totale CFU**: Somma dei crediti acquisiti.

### 1.2 Sistema di Autenticazione (`/auth`)
Per garantire la privacy e la corretta attribuzione dei dati in un contesto multi-utente demo:
*   **Login Endpoint**: Permette l'autenticazione tramite email e password.
*   **Gestione Sessioni**: Utilizzo di sessioni PHP server-side per mantenere lo stato di login.
*   **Protezione Route**: Gli endpoint sensibili (come le statistiche) verificano l'identità dell'utente prima di procedere.

### 1.3 Multi-tenancy (Supporto Multi-utente)
Il database e il layer di accesso ai dati sono stati aggiornati per associare ogni esame allo studente che lo ha sostenuto, garantendo l'isolamento dei dati.

---

## 2. Architettura Tecnica e Design Pattern

L'implementazione segue principi di progettazione software robusti per garantire manutenibilità ed estensibilità.

### 2.1 Strategy Pattern (Calcolo Medie)
**Descrizione**: Il calcolo delle medie è stato incapsulato in classi separate che implementano un'interfaccia comune.

**Componenti**:
*   `MediaStrategy` (Interface): Contratto `calcola(array $esami): float`.
*   `MediaAritmetica` (Concrete): Algoritmo media semplice.
*   `MediaPonderata` (Concrete): Algoritmo media pesata.

**Esempio Codice**:
```php
// src/server/api/strategies/MediaPonderata.php
class MediaPonderata implements MediaStrategy {
    public function calcola(array $esami): float {
        $sommaPonderata = 0;
        $totCFU = 0;
        foreach ($esami as $esame) {
            $sommaPonderata += ($esame['voto'] * $esame['cfu']);
            $totCFU += $esame['cfu'];
        }
        return $totCFU > 0 ? $sommaPonderata / $totCFU : 0;
    }
}
```

**Giustificazione**:
*   **Estensibilità**: Se in futuro si volesse aggiungere una "Media Aritmetica senza i 2 voti peggiori", basterebbe creare una nuova classe senza toccare il codice esistente (Open/Closed Principle).
*   **Testabilità**: Ogni algoritmo può essere testato unitariamente in isolamento.

### 2.2 Repository Pattern (Accesso ai Dati)
**Descrizione**: L'accesso diretto al database nei controller/API è stato sostituito da classi Repository dedicate.

**Componenti**:
*   `EsameRepository`: Gestisce le operazioni sulla tabella `esame`.
*   `UtenteRepository`: Gestisce le operazioni sulla tabella `utente` (es. login).

**Esempio Codice**:
```php
// src/server/repository/EsameRepository.php
public function findByStudent(int $studenteId): array {
    // Esegue una query filtrata, garantendo l'isolamento dei dati
    return $this->db->fetchAll(
        "SELECT * FROM applicazione.esame WHERE studente = :id", 
        ['id' => $studenteId]
    );
}
```

**Giustificazione**:
*   **Sicurezza**: Centralizza le query SQL, riducendo il rischio di injection e di errori logici (es. dimenticare la clausola WHERE).
*   **Disaccoppiamento**: La logica di business (API) non dipende dai dettagli dello schema database.

### 2.3 Gestione Sessioni (Sicurezza)
**Descrizione**: Utilizzo delle sessioni native di PHP per tracciare l'utente autenticato.

**Flusso**:
1.  **Login (`POST /api/login`)**: Verifica email/password. Se OK, `session_start()` e `$_SESSION['user_id'] = $id`.
2.  **Stats (`GET /api/stats`)**: Chiama `session_start()`. Se `$_SESSION['user_id']` manca, ritorna `401 Unauthorized`.

**Giustificazione**:
*   **Standard**: Meccanismo collaudato e sicuro per applicazioni web stateful.
*   **Semplicità**: Evita la complessità di gestire token JWT sul client per questo stadio del progetto.

---

## 3. Modifiche al Database

Per supportare le funzionalità sopra descritte, lo schema del database (`src/sql/init.sql`) è stato modificato:

### 3.1 Tabella `esame`
È stata aggiunta la relazione con l'utente per supportare la multi-utenza.

```sql
ALTER TABLE "applicazione"."esame" 
ADD COLUMN "studente" INTEGER NOT NULL;

ALTER TABLE "applicazione"."esame" 
ADD CONSTRAINT "esame_studente" 
FOREIGN KEY ("studente") REFERENCES "applicazione"."utente" ("utente_ID");
```

---

## 4. Riepilogo File Modificati/Creati

| File | Scopo |
| :--- | :--- |
| `src/server/api/stats.php` | Endpoint statistiche. Ora protetto da sessione. |
| `src/server/api/login.php` | [NEW] Endpoint di autenticazione. |
| `src/server/api/strategies/*` | [NEW] Classi Strategy per il calcolo delle medie. |
| `src/server/repository/EsameRepository.php` | [NEW] Astrazione accesso dati esami. |
| `src/sql/init.sql` | Aggiornato schema con colonna `studente`. |
| `src/server/api/endpoints.txt` | Mappatura rotte (`stats`, `login`). |
