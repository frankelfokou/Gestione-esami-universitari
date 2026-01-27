<?php
final class EsameRepository {
  public function __construct(private DB $pdo) {}

  public function all(): array {
    return $this->pdo->fetchAll("SELECT id, nome, data_esame FROM esame ORDER BY id DESC");
  }

  public function create(string $nome, string $data_esame): int {
    $sql = "INSERT INTO esame (nome, data_esame) VALUES (:nome, :data) RETURNING id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'data' => $data_esame]);
    // return (int)$stmt->fetchColumn();
    return $thi;
  }
}
