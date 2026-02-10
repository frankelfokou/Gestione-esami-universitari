<?php

  declare(strict_types = 1);

  class ResponseBuilder {
    private bool $success = false;
    private string $message = '';
    private ?string $error_code = null;
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
    
    public function withErrorCode(?string $error_code): ResponseBuilder {
      $this->error_code = $error_code;
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
      return new Response(
        $this->success,
        $this->message,
        $this->error_code,
        $this->endpoint,
        $this->auth,
        $this->data
      );
    }
  }

?>