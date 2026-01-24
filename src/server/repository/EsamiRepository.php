<?php
final class EsamiRepository {
  public function __construct(private PDO $pdo) {}

  public function all(): array {
    $stmt = $this->pdo->query("SELECT id, nome, data_esame FROM esami ORDER BY id DESC");
    return $stmt->fetchAll();
  }

  public function create(string $nome, string $data_esame): int {
    $sql = "INSERT INTO esami (nome, data_esame) VALUES (:nome, :data) RETURNING id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'data' => $data_esame]);
    return (int)$stmt->fetchColumn();
  }
}
