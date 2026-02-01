<?php
  declare(strict_types = 1);

  class Router {

    private $routes = [];

    public function __construct() {
        // Using a txt file for now, will probably be a JSON later
        $this->loadRoutesFromFile(dirname(__FILE__, 1) . "/api/endpoints.txt");
    }

    private function loadRoutesFromFile($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("Route file does not exist.");
        }

        $file = fopen($filePath, 'r');

        while(($line = fgets($file)) !== false) {
            $line = trim($line);

            if($line[0] === '#') {
                continue;
            }

            // Stores:
            // $tmpArray[0] => name
            // $tmpArray[1] => method
            // $tmpArray[2] => callback
            $tmpArray = explode(' ', $line);

            $this->routes[$tmpArray[0]] = [
                'method' => $tmpArray[1],
                'callback' => $tmpArray[2]
            ];
        }

        fclose($file);
    }

    public function checkRoute(string $route_name) : bool {
      foreach($this->routes as $key => $value) {
        if($key === $route_name)
          return true;
      }

      return false;
    }

    public function dispatch($name, $method) {
        if (isset($this->routes[$name])) {
            $route = $this->routes[$name] . 'EP';

            if ($route['method'] === $method) {
                call_user_func($route['callback']);
            } else {
                echo "Method not allowed!";
            }
        } else {
            echo "Route not found!";
        }
    }

  }
  
?>