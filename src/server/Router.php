<?php

  declare(strict_types = 1);

  require_once 'Request.php';
  require_once 'Response.php';

  class Router {
    protected array $routes = [];

    public function __construct() {
      $this->loadRoutesFromFile(dirname(__FILE__, 1) . "/api/endpoints.txt");
    }

    private function loadRoutesFromFile(string $filePath): void {
      if (!file_exists($filePath)) {
        throw new Exception("Route file non esiste.");
      }

      $file = fopen($filePath, 'r');

      while(($line = fgets($file)) !== false) {
        $line = trim($line);

        if (empty($line) || $line[0] === '#') {
          continue;
        }

        $parts = explode(' ', $line);

        if (count($parts) !== 3) {
          continue;
        }

        $routeName = $parts[0];
        $httpMethod = strtoupper($parts[1]);
        $commandClassName = $parts[2] . 'Command';

        $this->routes[$routeName] = [
          'method' => $httpMethod,
          'command' => $commandClassName
        ];
      }

      fclose($file);
    }

    public function checkRoute(string $routeName): bool {
      return isset($this->routes[$routeName]);
    }

    public function dispatch(string $routeName, Request $request): Response {
      if (!$this->checkRoute($routeName)) {
        return $this->createErrorResponse('Route non trovata', $routeName, 'ROUTE_NON_TROVATA');
      }

      $route = $this->routes[$routeName];

      if ($route['method'] !== $request->getMethod()) {
        return $this->createErrorResponse('Metodo non consentito', $routeName, 'METODO_NON_CONSENTITO');
      }

      $commandClassName = $route['command'];

      if (!class_exists($commandClassName)) {
        return $this->createErrorResponse('Command non trovato', $routeName, 'COMMAND_NON_TROVATO');
      }

      $command = new $commandClassName();

      if (!$command instanceof CommandInterface) {
        return $this->createErrorResponse('Command non valido', $routeName, 'COMMAND_NON_VALIDO' );
      }

      try {
        return $command->execute($request);
      } catch (Exception $e) {
        return $this->createErrorResponse('Esecuzione fallita: ' . $e->getMessage(), $routeName, 'ERRORE_DI_ESECUZIONE');
      }
    }

    private function createErrorResponse(string $message, string $endpoint, string $errorCode): Response {
      return (new ResponseBuilder())
        ->withSuccess(false)
        ->withMessage($message)
        ->withEndpoint($endpoint)
        ->withErrorCode($errorCode)
        ->withAuth(false)
        ->build();
    }

    public function getRouteFromUri(string $uri): ?string {
      $uri = trim($uri, '/');

      $uri = strtok($uri, '?');

      foreach ($this->routes as $routeName => $routeInfo) {
        if ($uri === $routeName) {
          return $routeName;
        }
      }

      return null;
    }

    public function handleRequest(Request $request): Response {
      $routeName = $this->getRouteFromUri($request->getUri());

      if ($routeName === null) {
        return $this->createErrorResponse('Route non trovata per l\'URI: ' . $request->getUri(), 'unknown', 'ROUTE_NON_TROVATA');
      }

      return $this->dispatch($routeName, $request);
    }
  }

?>