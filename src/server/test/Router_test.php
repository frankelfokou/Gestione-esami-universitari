<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Router test</title>
  <link rel="icon" type="image/x-icon" href="../favicon.ico">
  <link rel="stylesheet" type="text/css" href="./testStyle.css" />
</head>
<body>
  <pre>
    <h3 class="test_name">Testing Router</h3>



    <?php
      require_once(dirname(__FILE__, 1) . '/Assertion.php');
      require_once(dirname(__FILE__, 2) . '/Router.php');

      Class TestRouter {
        private $router;

        public function __construct() {
          $this->router = new Router(dirname(__FILE__, 2) . "/api/endpoints.txt");
        }

        public function testEP(string $epName) : void {
          try {
            Assertion::assertTrue($this->router->checkRoute($epName));
            echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(\"{$epName}\").\n</p>";
          } catch (Exception $e) {
            echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(\"{$epName}\") ". $e->getMessage() . ".\n</p>";
          }
        }

      }

      $test_router = new TestRouter();

      // Testing the EndPoints
      $test_router->testEP("");                   //Should fail X
      $test_router->testEP("login");              //Should pass O
      $test_router->testEP("register");           //Should pass O
      $test_router->testEP("randomLongAssName");  //Should fail X
      $test_router->testEP("esami");              //Should pass O

      // Testing the results
    ?>



  </pre>
</body>
</html>