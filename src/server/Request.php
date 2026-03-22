<?php

  declare(strict_types = 1);

  class Request {
    private string $method;
    private string $uri;
    private array $headers;
    private array $body;
    private array $queryStrings;

    public function __construct(string $method = 'GET', string $uri = '/', array $headers = [], array $body = [], array $queryStrings = []) {
      $this->method = strtoupper($method);
      $this->uri = $uri;
      $this->headers = $headers;
      $this->body = $body;
      $this->queryStrings = $queryStrings;
    }

    public static function createBaseRequest(): Request {
      $method = $_SERVER['REQUEST_METHOD'];
      $uri = $_SERVER['REQUEST_URI'];
      $headers = getallheaders();

      // Parameteri della pagina, es: relation.php?variable1/variable2/variable3 -> tutto cio' che c'e' dopo il ? (variable1/variable2/variable3)
      $queryStrings = [];

      if (isset($_SERVER['QUERY_STRING'])) {
        parse_str($_SERVER['QUERY_STRING'], $queryStrings);
      }

      $queryStrings = array_merge($queryStrings, $_GET);

      // Contenuto da metodi POST e PUT (in realta' sono tutti POST quelli di inserimento dati)
      $body = [];

      if ($method === 'POST' || $method === 'PUT') {
        $body = $_POST;
      }

      return new Request($method, $uri, $headers, $body, $queryStrings);
    }

    public function getMethod(): string {
      return $this->method;
    }

    public function getUri(): string {
      return $this->uri;
    }

    public function getHeaders(): array {
      return $this->headers;
    }

    // Per singoli header
    // ?string significa che o ritorna una stringa o un nullo
    public function getHeader(string $name): ?string {
      foreach ($this->headers as $key => $value) {
        // Pulizia della stringa e conversione a tutto minuscolo (anche maiuscolo andava bene, ma cosi' ho scelto)
        if (strtolower(trim($key)) === strtolower(trim($name))) {
          return $value;
        }
      }
 
      return null;
    }

    public function getBody(): array {
      return $this->body;
    }

    // Per un elemento del corpo della Request
    public function getBodyParam(string $key, $default = null) {
      // Operatore terniario, cioe' x ? a : b, se x e' vera fai a, altrimenti fai b
      return isset($this->body[$key]) ? $this->body[$key] : $default;
    }

    public function getQueryStrings(): array {
      return $this->queryStrings;
    }

    // Per il singolo parametro
    public function getQueryString(string $key, $default = null) {
      return isset($this->queryStrings[$key]) ? $this->queryStrings[$key] : $default;
    }
  }

?>