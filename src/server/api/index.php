<?php

  declare(strict_types=1);

  require_once(dirname(__FILE__, 2) . "/src/Router.php");
  require_once(dirname(__FILE__, 2) . "/src/Request.php");
  require_once(dirname(__FILE__, 2) . "/src/Response.php");

  // Classi Command
  require_once(dirname(__FILE__, 2) . "/src/api/commands/CommandInterface.php");
  require_once(dirname(__FILE__, 2) . "/src/api/commands/LoginCommand.php");
  require_once(dirname(__FILE__, 2) . "/src/api/commands/LogoutCommand.php");
  require_once(dirname(__FILE__, 2) . "/src/api/commands/RegisterCommand.php");
  require_once(dirname(__FILE__, 2) . "/src/api/commands/ExamsCommand.php");
  require_once(dirname(__FILE__, 2) . "/src/api/commands/StatsCommand.php");

  // Includo le funzioni EP
  require_once __DIR__ . '/stats.php';  // This should contain statsEP() function
  require_once __DIR__ . '/login.php';  // This should contain loginEP() function
  require_once __DIR__ . '/logout.php'; // This should contain logoutEP() function

  $router = new Router();

  function sanitize_url($url): string {
    // Rimuovo gli spazi " " all'inizio e alla fine
    $url = trim($url, ' ');
    // Rimuovo gli slash "/" all'inizio e alla fine
    $url = trim($url, '/');
    // Rimuovo la parte "api" (se presente)
    $url = str_replace('api', '', $url);
    // Rimuovo di nuovo gli slash "/" all'inizio e alla fine PER SICUREZZA
    $url = trim($url, '/');
    
    // Rimuovo i query parameters
    $url = strtok($url, '?');

    return $url;
  }

  $request = Request::createBaseRequest();

  // URI ripulito
  $sanitizedUri = sanitize_url($_SERVER['REQUEST_URI']);

  // Gestione delle Request con Router
  try {
      $response = $router->handleRequest($request);
      
      // Metto il codice dello stato HTTP (successo o fallimento) nella Response
      http_response_code($response->getSuccess() ? 200 : 400);
      
      // Metto content-type in JSON
      header('Content-Type: application/json');
      
      // Output la Response in JSON
      echo $response->convertToJson();
      
  } catch (Exception $e) {
      // Gestione degli errori inattesi
      http_response_code(500);
      header('Content-Type: application/json');
      
      // Creo un error response usando ResponseBuilder
      $errorResponse = (new ResponseBuilder())
          ->withSuccess(false)
          ->withMessage('Errore interno del server: ' . $e->getMessage())
          ->withEndpoint('system_error')
          ->withErrorCode('INTERNAL_ERROR')
          ->build();
      
      echo $errorResponse->convertToJson();
  }

?>