<?php
require_once dirname(__DIR__) . '/config/DatabaseWrapper.php';

final class EsameRepository
{
  public function __construct(private DatabaseWrapper $db)
  {
  }

  public function all(): array
  {
    return $this->db->fetchAll("SELECT * FROM applicazione.esame ORDER BY esame_ID DESC");
  }

  /*
   * Finds exams by student ID.
   */
  public function findByStudent(int $studenteId): array
  {
    return $this->db->fetchAll(
      "SELECT * FROM applicazione.esame WHERE studente = :id ORDER BY esame_ID DESC",
      ['id' => $studenteId]
    );
  }

  public function create(array $data): int
  {
    // Implementation pending
    return 0;
  }
}
