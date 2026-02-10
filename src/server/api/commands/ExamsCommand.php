<?php

  declare(strict_types = 1);

  require_once(__DIR__ . '/CommandInterface.php');

  require_once(dirname(__FILE__, 3) . '/Request.php');
  require_once(dirname(__FILE__, 3) . '/Response.php');

  class ExamsCommand implements CommandInterface {
    public function execute(Request $request): Response {
      $result = examsEP();
      
      return (new ResponseBuilder())
        ->withSuccess($result['success'])
        ->withMessage($result['message'])
        ->withEndpoint('exams')
        ->withAuth(true)
        ->withData($this->extractData($result))
        ->build();
    }

    private function extractData(array $result): array {
      $data = [];
      foreach ($result as $key => $value) {
        if ($key !== 'success' && $key !== 'message') {
          $data[$key] = $value;
        }
      }
      return $data;
    }
  }

?>