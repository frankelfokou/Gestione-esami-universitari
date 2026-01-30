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
   * Simulates finding exams by student ID.
   * Since there is no link between exams and students yet,
   * this returns all exams to allow testing statistics.
   */
  public function findByStudent(int $studenteId): array
  {
    // SECURITY SIMULATION: In a real app, this would filter by student_id
    // return $this->db->fetchAll("SELECT * FROM applicazione.esame WHERE student_id = :id", ['id' => $studenteId]);

    // For now, return all exams so we can calculate stats on the sample data
    return $this->all();
  }

  public function create(array $data): int
  {
    // Implementation pending
    return 0;
  }
}
