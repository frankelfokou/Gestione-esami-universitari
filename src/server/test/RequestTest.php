<?php

  declare(strict_types = 1);

  require_once(__DIR__. '/Assertion.php');
  require_once(dirname(__FILE__, 2) . '/Request.php');

  class RequestTest {
    public static function runAll(): void {
      echo "<h2>Test della classe Request</h2><br />";

      echo "<h4 class=\"test_name\">1. Test della creazione di una Request di base</h4><br />";
      self::testRequestCreation();

      echo "<h4 class=\"test_name\">2. Test dei getters di una Request</h4><br />";
      self::testRequestGetters();

      echo "<h4 class=\"test_name\">3. Test dell'accesso al corpo di una Request</h4><br />";
      self::testRequestBodyAccess();

      echo "<h4 class=\"test_name\">4. Test dell'accesso dei parametri di una Request</h4><br />";
      self::testRequestQueryParams();

      echo "<h4 class=\"test_name\">5. Test dell'accesso degli headers di una Request</h4><br />";
      self::testRequestHeaders();

      echo "<h4 class=\"test_name\">6. Test dei valori di default di una Request</h4><br />";
      self::testDefaultValues();
    }

    private static function testRequestCreation(): void {
      echo "<h3> - Creazione della Request tramite costruttore - </h3>";

      $request = new Request(
        'POST',
        '/api/login',
        ['Content-Type' => 'application/json'],
        ['username' => 'john', 'password' => 'secret'],
        ['redirect' => 'dashboard']
      );

      try {
        Assertion::assertInstanceOf('Request', $request);
        Assertion::assertEquals('POST', $request->getMethod());
        Assertion::assertEquals('/api/login', $request->getUri());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Request)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Request->getMethod)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Request->getUri)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Request) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Request->getMethod) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Request->getUri) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testRequestGetters(): void {
      $headers = ['Authorization' => 'Bearer token123', 'Accept' => 'application/json'];
      $body = ['action' => 'create', 'data' => ['name' => 'Test']];
      $query = ['page' => '1', 'limit' => '20'];

      $request = new Request('PUT', '/api/users', $headers, $body, $query);

      try {
        Assertion::assertEquals('PUT', $request->getMethod());
        Assertion::assertEquals('/api/users', $request->getUri());
        Assertion::assertArrayEquals($headers, $request->getHeaders());
        Assertion::assertArrayEquals($body, $request->getBody());
        Assertion::assertArrayEquals($query, $request->getQueryStrings());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Request->getMethod)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Request->getUri)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertArrayEquals(Request->getHeaders)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertArrayEquals(Request->getBody)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertArrayEquals(Request->getQueryStrings)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(getMethod) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(getUri) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertArrayEquals(getHeaders) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertArrayEquals(getBody) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertArrayEquals(getQueryStrings) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testRequestBodyAccess(): void {
      $body = [
        'username' => 'mario_rossi',
        'email' => 'rossi@esempio.com',
        'age' => 25
      ];

      $request = new Request('POST', '/api/register', [], $body, []);

      try {
        Assertion::assertEquals('mario_rossi', $request->getBodyParam('username'));
        Assertion::assertEquals('rossi@esempio.com', $request->getBodyParam('email'));
        Assertion::assertEquals(25, $request->getBodyParam('age'));
        Assertion::assertNull($request->getBodyParam('nonexistent'));
        Assertion::assertEquals('default', $request->getBodyParam('nonexistent', 'default'));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(mario_rossi)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(rossi@esempio.com)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(25)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull()\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(default)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(mario_rossi) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(rossi@esempio.com) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(25) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNull() " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(default) " . $e->getMessage() . ".\n</p>";
      }

      echo "PASS\n";
    }

    private static function testRequestQueryParams(): void {
      $query = [
        'search' => 'php',
        'sort' => 'date',
        'order' => 'desc'
      ];

      $request = new Request('GET', '/api/search', [], [], $query);

      try {
        Assertion::assertEquals('php', $request->getQueryString('search'));
        Assertion::assertEquals('date', $request->getQueryString('sort'));
        Assertion::assertEquals('desc', $request->getQueryString('order'));
        Assertion::assertNull($request->getQueryString('page'));
        Assertion::assertEquals(10, $request->getQueryString('page', 10));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(php)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(date)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(desc)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull(request->getQueryString[page])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(10)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(php) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(date) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(desc) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNull(request->getQueryString[page]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(10) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testRequestHeaders(): void {
      $headers = [
          'Content-Type' => 'application/json',
          'X-API-Key' => 'secret-key-123',
          'User-Agent' => 'TestClient/1.0'
      ];

      $request = new Request('GET', '/api/data', $headers, [], []);

      try {
        Assertion::assertEquals('application/json', $request->getHeader('Content-Type'));
        Assertion::assertEquals('secret-key-123', $request->getHeader('X-API-Key'));
        Assertion::assertEquals('TestClient/1.0', $request->getHeader('User-Agent'));
        Assertion::assertNull($request->getHeader('auth'));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(application/json)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(secret-key-123)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(TestClient/1.0)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull(request->getHeader[auth])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(getMethod) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testDefaultValues(): void {
      $request = new Request();

      try {
        Assertion::assertEquals('GET', $request->getMethod());
        Assertion::assertEquals('/', $request->getUri());
        Assertion::assertArrayEquals([], $request->getHeaders());
        Assertion::assertArrayEquals([], $request->getBody());
        Assertion::assertArrayEquals([], $request->getQueryStrings());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(GET)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(/)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertArrayEquals(request->getHeaders)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertArrayEquals(request->getBody)\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertArrayEquals(request->getQueryStrings)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(GET) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(/) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertArrayEquals(request->getHeaders) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertArrayEquals(request->getBody) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertArrayEquals(request->getQueryStrings) " . $e->getMessage() . ".\n</p>";
      }
    }
  }

  // Run tests if this file is executed directly
  if (basename($_SERVER['PHP_SELF']) === 'RequestTest.php') {
      try {
          RequestTest::runAll();
      } catch (Exception $e) {
          echo "\n❌ Test failed: " . $e->getMessage() . "\n";
          exit(1);
      }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RequestTest</title>
  <link rel="stylesheet" href="testStyle.css">
</head>
<body>
</body>
</html>