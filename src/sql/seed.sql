-- =========================
-- SEED DATI FITTIZI
-- =========================
BEGIN;

-- pulizia (facoltativa)
TRUNCATE TABLE
  "applicazione"."esame",
  "applicazione"."insegnamento",
  "applicazione"."corso",
  "applicazione"."carriera",
  "applicazione"."utente"
RESTART IDENTITY CASCADE;



-- -------------------------
-- UTENTI (date >= 2008-01-01 come da CHECK)
-- -------------------------
INSERT INTO "applicazione"."utente"
("utente_ID","nome","cognome","data_di_nascita","email","password")
OVERRIDING SYSTEM VALUE
VALUES
  (1,'Luca','Rossi','2008-02-14','luca.rossi@example.com','$2y$10$fakehash1'),
  (2,'Giulia','Bianchi','2009-07-03','giulia.bianchi@example.com','$2y$10$fakehash2'),
  (3,'Marco','Verdi','2008-11-21','marco.verdi@example.com','$2y$10$fakehash3'),
  (4,'Sara','Neri','2010-01-09','sara.neri@example.com','$2y$10$fakehash4'),
  (5,'Elena','Gallo','2009-03-30','elena.gallo@example.com','$2y$10$fakehash5');

-- -------------------------
-- CORSI
-- Constraint: base_laurea=60, scala_voto=110 (come inteso dal tuo CHECK)
-- Campo "insegnamenti" nel tuo schema è integer e viene usato dalla FK invertita:
-- insegnamento.insegnamento_ID -> corso.insegnamenti
-- quindi qui metto insegnamenti = 201/202/203 così poi posso creare insegnamenti con quegli ID.
-- -------------------------
INSERT INTO "applicazione"."corso"
("corso_ID","nome","insegnamenti","tipo","base_laurea","scala_voto","cfu_totali")
OVERRIDING SYSTEM VALUE
VALUES
  (101,'Informatica',201,'LT',60,110,180),
  (102,'Economia',   202,'LT',60,110,180),
  (103,'Ingegneria', 203,'LT',60,110,180);

-- -------------------------
-- CARRIERE
-- Nota: qui "corso" è solo un integer (FK è sull'altra tabella: corso.corso_ID -> carriera.corso)
-- quindi preparo valori corso = 101/102/103 che userò dopo in "corso".
-- -------------------------
INSERT INTO "applicazione"."carriera"
("carriera_ID","studente","corso","stato","data_inizio","data_fine")
OVERRIDING SYSTEM VALUE
VALUES
  (1,1,101,'ATTIVA','2025-10-01',NULL),
  (2,2,101,'ATTIVA','2025-10-01',NULL),
  (3,3,102,'ATTIVA','2024-10-01',NULL),
  (4,4,103,'ATTIVA','2025-03-01',NULL),
  (5,5,102,'CHIUSA','2024-10-01','2025-09-15');
-- -------------------------


-- -------------------------
-- INSEGNAMENTI
-- FK invertita: insegnamento_ID deve esistere come valore in corso.insegnamenti
-- quindi creo almeno 201/202/203 (poi aggiungo altri insegnamenti "normali" senza vincolo,
-- ma ATTENZIONE: con la tua FK così com'è, anche quelli dovrebbero matchare un corso.insegnamenti.
-- Per non rompere la FK, tengo SOLO ID che matchano 201/202/203.
-- -------------------------
INSERT INTO "applicazione"."insegnamento"
("insegnamento_ID","corso","nome","cfu","anno","obbligatorio")
OVERRIDING SYSTEM VALUE
VALUES
  (201,101,'BasiDati',12,2,true),
  (202,102,'Microeco', 9,1,true),
  (203,103,'Analisi1',12,1,true);

-- -------------------------
-- ESAMI (FK normale: esame.insegnamento -> insegnamento.insegnamento_ID)
-- -------------------------
INSERT INTO "applicazione"."esame"
("esame_ID","insegnamento","data_esame")
OVERRIDING SYSTEM VALUE
VALUES
  (1,201,'2025-01-20'),
  (2,201,'2025-06-18'),
  (3,201,'2025-09-10'),
  (4,202,'2025-02-05'),
  (5,202,'2025-07-01'),
  (6,203,'2025-01-15'),
  (7,203,'2025-06-10'),
  (8,203,'2025-09-02');

COMMIT;

