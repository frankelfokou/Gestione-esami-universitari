# Documentazione Implementazione Statistiche

## Panoramica
Il modulo statistiche è responsabile del calcolo e della fornitura di metriche sul rendimento dello studente, specificamente la media aritmetica, la media ponderata, la proiezione del voto di laurea e il totale dei CFU acquisiti.

L'architettura gestisce la logica di calcolo esponendo i dati tramite un'API REST.

## Design Pattern: Strategy
Per il calcolo delle medie è stato utilizzato il **Strategy Pattern**. Questo pattern comportamentale permette di definire una famiglia di algoritmi, incapsularli e renderli intercambiabili.

### Componenti
1.  **Strategy Interface (`MediaStrategy`)**: Definisce il contratto comune per tutti gli algoritmi di calcolo della media.
    ```php
    interface MediaStrategy {
        public function calcola(array $esami): float;
    }
    ```
2.  **Concrete Strategies**:
    *   **`MediaAritmetica`**: Implementa il calcolo della media aritmetica semplice.
    *   **`MediaPonderata`**: Implementa il calcolo della media pesata sui CFU.
3.  **Context**: La funzione `statsEP` agisce come client che istanzia e utilizza le strategie.

## Integrazione dei Componenti

### 1. Data Access Layer (`EsameRepository` & `DatabaseWrapper`)
Per isolare l'accesso ai dati è stato introdotto un Repository Pattern.
*   **`DatabaseWrapper`**: Gestisce la connessione low-level a PostgreSQL.
*   **`EsameRepository`**: Utilizza il wrapper per eseguire query. Espone il metodo `findByStudent($id)` per recuperare gli esami di uno specifico studente.

### 2. Router e API Dispatcher (`Router`, `index.php`)
Il sistema di routing gestisce la richiesta HTTP per le statistiche.
*   **Configurazione**: `src/server/api/endpoints.txt` contiene la regola `stats GET statsEP`.
*   **Flusso**: Il `Router` mappa `/stats` alla funzione `statsEP`.

### 3. Frontend (`ExamModel`, `ExamPresenter`)
Il client consuma l'API per visualizzare i dati.
*   **`ExamModel.js`**: `getStats()` effettua una chiamata asincrona `fetch('/api/stats')`.
*   **`ExamPresenter.js`**: Attende i dati e aggiorna la View.

## Simulazione Architetturale (Sicurezza e Multi-utenza)
Attualmente, il sistema non dispone di un layer di autenticazione completo. Per permettere lo sviluppo delle statistiche supportando concettualmente la multi-utenza, è stata implementata una **simulazione**:

*   **Repository Layer**: Il metodo `EsameRepository::findByStudent(int $studenteId)` simula il filtro.
    *   *Attuale*: Restituisce tutti gli esami (mock).
    *   *Futuro*:filtrerà con `WHERE studente_id = :id`.
*   **API Layer**: L'endpoint `statsEP` simula un utente loggato (es. `$userId = 1`) e interroga il repository.

> [!NOTE]
> Questa struttura permette di implementare la sicurezza reale (Autenticazione e Schema DB) in futuro modificando solo il Repository e l'iniezione dell'ID utente, mantenendo intatta la logica di calcolo.

## Flussi di Lavoro (Workflows)
1.  **Request**: `GET /api/stats`.
2.  **Dispatch**: Il router invoca `statsEP`.
3.  **Data Access**: `statsEP` determina l'utente (simulato) e chiama `EsameRepository::findByStudent(1)`.
4.  **Calculation**: Vengono applicate `MediaAritmetica` e `MediaPonderata` sui dati restituiti.
5.  **Response**: Il backend restituisce il JSON con le metriche.
6.  **Visualization**: Il frontend riceve il JSON e aggiorna la dashboard.
