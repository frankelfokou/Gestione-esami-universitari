<?php
require_once __DIR__ . '/../config/Database.php';

try {
    $db  = new Database();
    $pdo = $db->pdo();

    $stmt = $pdo->query("SELECT current_database() AS db, current_user AS usr, version() AS ver");
    $row  = $stmt->fetch();
    echo "Utente: " . $row['usr'] ."<br>";
    echo "Versione postgres: " . $row['ver'];

    $sql = 'SELECT "esame_ID", "insegnamento", "data_esame"
            FROM "applicazione"."esame"
            ORDER BY "data_esame" DESC, "esame_ID" DESC';

    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);



} catch (Throwable $e) {
    echo "Connessione FALLITA: " . $e->getMessage();
}
