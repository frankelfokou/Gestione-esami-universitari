<?php
require __DIR__ . '/../config/Database.php';
require __DIR__ . '/../repository/UtenteRepository.php';

$db = new Database();
$repo = new UtenteRepository($db->pdo());

header('Content-Type: application/json; charset=utf-8');

$all = $repo->all();

echo json_encode([
    'count' => count($all),
    'first' => $all[0] ?? null
], JSON_PRETTY_PRINT);

