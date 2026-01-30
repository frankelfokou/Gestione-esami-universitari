<?php
  declare(strict_types = 1);

  require_once(dirname(__FILE__, 2) . "/Router.php");

  $router = new Router();

  function sanitize_url($url) : string {
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

  if($router->checkRoute(sanitize_url($_SERVER['REQUEST_URI']))) {
    http_response_code(200);
    echo "Route exists!";
  }
  else {
    http_response_code(404);
    die();
  }
?>