<?php

  declare(strict_types = 1);

  class Response {
    private bool $success;
    private string $message;
    private ?string $errorCode;
    private string $endpoint;
    private bool $auth;
    private array $data;

    public function __construct(bool $success = false, string $message = '', ?string $errorCode = null, string $endpoint = '', bool $auth = false, array $data = []) {
      $this->success = $success;
      $this->message = $message;
      $this->errorCode = $errorCode;
      $this->endpoint = $endpoint;
      $this->auth = $auth;
      $this->data = $data;
    }

    public function getSuccess(): bool {
      return $this->success;
    }

    public function getMessage(): string {
      return $this->message;
    }

    public function getErrorCode(): ?string {
      return $this->errorCode;
    }

    public function getEndpoint(): string {
      return $this->endpoint;
    }

    public function requiresAuth(): bool {
      return $this->auth;
    }

    public function getData(): array {
      return $this->data;
    }

    public function convertToJson(): string {
      $responseArray = [
        'success' => $this->success,
        'message' => $this->message,
        'endpoint' => $this->endpoint,
        'auth' => $this->auth
      ];

      if ($this->errorCode !== null) {
        $responseArray['error_code'] = $this->errorCode;
      }

      if (!empty($this->data)) {
        $responseArray['data'] = $this->data;
      }

      $json = json_encode($responseArray, JSON_PRETTY_PRINT);

      if ($json === false) {
        return json_encode([
          'success' => false, 
          'message' => 'JSON encoding failed',
          'endpoint' => 'system_error'
        ]);
      }

      return $json;
    }
  }

/* ==================================================================================== */

  class ResponseBuilder {
    private bool $success = false;
    private string $message = '';
    private ?string $errorCode = null;
    private string $endpoint = '';
    private bool $auth = false;
    private array $data = [];

    public function withSuccess(bool $success): ResponseBuilder {
      $this->success = $success;
      return $this;
    }

    public function withMessage(string $message): ResponseBuilder {
      $this->message = $message;
      return $this;
    }

    public function withErrorCode(?string $errorCode): ResponseBuilder {
      $this->errorCode = $errorCode;
      return $this;
    }

    public function withEndpoint(string $endpoint): ResponseBuilder {
      $this->endpoint = $endpoint;
      return $this;
    }

    public function withAuth(bool $auth): ResponseBuilder {
      $this->auth = $auth;
      return $this;
    }

    public function withData(array $data): ResponseBuilder {
      $this->data = $data;
      return $this;
    }

    public function build(): Response {
      if (empty($this->endpoint)) {
        throw new Exception('Endpoint richiesto dalla Response');
      }

      return new Response($this->success, $this->message, $this->errorCode, $this->endpoint, $this->auth, $this->data);
    }
  }

?>