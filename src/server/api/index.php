<?php
declare(strict_types=1);

require_once(dirname(__FILE__, 2) . "/Router.php");

$router = new Router();

function sanitize_url($url): string
{
  //Removing trail spaces(at the beginning and the end of the string)
  $url = trim($url, ' ');
  //Removing trail slashes "/" (at the beginning and the end of the string)
  $url = trim($url, '/');
  //Removing the "api" portion
  $url = str_replace('api', '', $url);
  //Removing trail slashes "/" again
  $url = trim($url, '/');

  return $url;
}


require_once __DIR__ . '/stats.php';

$uri = sanitize_url($_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

if ($router->checkRoute($uri)) {
  $router->dispatch($uri, $method);
} else {
  http_response_code(404);
  echo json_encode(['error' => 'Route not found']);
  die();
}
?>