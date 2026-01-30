# Documentazione Implementazione Statistiche

## Panoramica

Il modulo statistiche è responsabile del calcolo e della fornitura di metriche sul rendimento dello studente, specificamente la media aritmetica, la media ponderata, la proiezione del voto di laurea e il totale dei CFU acquisiti.

L'architettura gestisce la logica di calcolo esponendo i dati tramite un'API REST.

## Design Pattern: Strategy

Per il calcolo delle medie è stato utilizzato il **Strategy Pattern**. Questo pattern comportamentale permette di definire una famiglia di algoritmi, incapsularli e renderli intercambiabili.

### Componenti

1. **Strategy Interface (`MediaStrategy`)**: Definisce il contratto comune per tutti gli algoritmi di calcolo della media.
   ```php
   interface MediaStrategy {
       public function calcola(array $esami): float;
   }
   ```
2. **Concrete Strategies**:
   * **`MediaAritmetica`**: Implementa il calcolo della media aritmetica semplice (somma voti / numero esami).
   * **`MediaPonderata`**: Implementa il calcolo della media pesata sui CFU (somma (voto * cfu) / totale cfu).
3. **Context**: In questo caso, la funzione `statsEP` agisce come client che istanzia e utilizza le strategie concrete in base alla necessità di calcolare entrambi i valori per la risposta API.

### Vantaggi

* **Estensibilità**: È possibile aggiungere nuove modalità di calcolo (es. media escludendo i voti più bassi) creando una nuova classe che implementa `MediaStrategy`, senza modificare il codice esistente.
* **Separazione delle responsabilità**: Ogni algoritmo di calcolo è isolato nella propria classe.

## Integrazione dei Componenti

### 1. Database (`DatabaseWrapper`)

Il modulo interagisce con il database PostgreSQL tramite la classe `DatabaseWrapper`.

* **Query**: Viene eseguita una `SELECT * FROM applicazione.esame` per recuperare tutti gli esami sostenuti.
* **Dati utilizzati**: Le colonne `voto` e `cfu` sono essenziali per i calcoli delle strategie.

### 2. Router e API Dispatcher (`Router`, `index.php`)

Il sistema di routing gestisce la richiesta HTTP per le statistiche.

* **Configurazione**: Nel file `src/server/api/endpoints.txt` è stata aggiunta la regola:
  ```text
  stats GET statsEP
  ```
* **Flusso**:
  1. Il `Router` legge `endpoints.txt` e mappa l'URI `/stats` (dopo la sanitizzazione) alla funzione di callback `statsEP`.
  2. `index.php` intercetta la richiesta, verifica la route tramite `router->checkRoute()` e, se trovata, esegue il dispatch chiamando `statsEP` (definita in `src/server/api/stats.php`).

### 3. Frontend (`ExamModel`, `ExamPresenter`)

Il client consuma l'API per visualizzare i dati.

* **`ExamModel.js`**: Il metodo `getStats()` è stato modificato per essere asincrono. Effettua una chiamata `fetch('/api/stats')` e restituisce il JSON ricevuto.
* **`ExamPresenter.js`**: Gestisce l'aggiornamento della View. Poiché `getStats()` è ora asincrona, il presenter attende (`await`) la risposta prima di passare i dati alla View per il rendering nella dashboard.

## Flussi di Lavoro (Workflows)

### Richiesta Statistiche (Pagina Dashboard)

1. **Inizializzazione**: L'utente accede alla dashboard.
2. **Richiesta Client**: `ExamPresenter` chiama `ExamModel.getStats()`.
3. **Chiamata API**: Il browser invia una richiesta `GET /api/stats`.
4. **Routing Backend**:
   * Nginx inoltra la richiesta a PHP.
   * `src/server/api/index.php` riceve la richiesta.
   * `Router` identifica che `/stats` corrisponde a `statsEP`.
5. **Elaborazione (Backend)**:
   * Viene eseguita la funzione `statsEP`.
   * Viene istanziata la connessione al DB.
   * Vengono recuperati i dati grezzi degli esami (`SELECT`).
   * Vengono istanziate le strategie `MediaAritmetica` e `MediaPonderata`.
   * I metodi `calcola()` vengono eseguiti sui dati recuperati.
   * Vengono calcolati proiezione voto di laurea e totale CFU.
6. **Risposta**: Il backend restituisce un oggetto JSON:
   ```json
   {
     "mediaA": 28.5,
     "mediaP": 28.7,
     "proiezione": 105.2,
     "cfuTotali": 60
   }
   ```
7. **Visualizzazione**: `ExamModel` riceve il JSON. `ExamPresenter` estrae i valori e invoca `view.updateStats()` per aggiornare il DOM.

## Considerazioni sulla Sicurezza e Multi-utenza

> [!WARNING]
> **Limitazione Attuale**: L'implementazione corrente è basata su una versione semplificata dello schema database e dell'applicazione. Attualmente non è implementato un sistema di autenticazione completo (`loginEP` e `registerEP` non sono presenti nel codice sorgente analizzato) e la tabella `esame` non presenta un collegamento esplicito all'utente (es. colonna `studente_id`).

### Problema Identificato
Attualmente, la chiamata a `statsEP` recupera **tutti** gli esami presenti nella tabella `applicazione.esame`. In un contesto multi-utente reale, questo comporterebbe che un utente vedrebbe le statistiche calcolate sui voti di tutti gli studenti, violando la privacy e la correttezza dei dati.

### Soluzione Proposta (Roadmap)
Per garantire che le statistiche siano calcolate solo per lo studente richiedente, è necessario implementare le seguenti modifiche:

1.  **Autenticazione**: Implementare un sistema di sessione (es. PHP Session o JWT) che identifichi l'utente loggato ad ogni richiesta API.
2.  **Schema Database**: Aggiungere una chiave esterna alla tabella `esame` (o unire tramite `carriera`) per associare ogni voto allo studente specifico:
    ```sql
    ALTER TABLE applicazione.esame ADD COLUMN studente_id INTEGER REFERENCES applicazione.utente(utente_ID);
    ```
3.  **Filtraggio Query**: Modificare la query in `stats.php` per filtrare in base all'ID utente in sessione:
    ```php
    $userId = $_SESSION['user_id'];
    $esami = $db->fetchAll("SELECT * FROM applicazione.esame WHERE studente_id = :id", ['id' => $userId]);
    ```
