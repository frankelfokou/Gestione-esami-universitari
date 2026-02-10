<?php

  declare(strict_types = 1);

  require_once(__DIR__. '/Assertion.php');
  require_once(dirname(__FILE__, 2) . '/Response.php');

  class ResponseTest {
    public static function runAll(): void {
      echo "<h2>Test della classe Response con design pattern Builder</h2><br />";

      echo "<h4 class=\"test_name\">1. Test della creazione di una Response di base</h4><br />";
      self::testResponse();

      echo "<br />";
      echo "<h4 class=\"test_name\">2. Test dei metodi con design pattern Builder</h4><br />";
      self::testBuilderMethods();
      
      echo "<br />";
      echo "<h4 class=\"test_name\">3. Test delle proprieta' di Response</h4><br />";
      self::testResponseProperties();
      
      echo "<br />";
      echo "<h4 class=\"test_name\">4. Test della conversione in JSON</h4><br />";
      self::testJsonConversion();
      
      echo "<br />";
      echo "<h4 class=\"test_name\">5. Test della gestione degli errori</h4><br />";
      self::testErrorHandling();
      
      echo "<br />";
      echo "<h4 class=\"test_name\">6. Test di validazione di Builder</h4><br />";
      self::testBuilderValidation();
      
      echo "<br />";
      echo "<h4 class=\"test_name\">7. Test su Response complesse</h4><br />";
      self::testComplexResponses();
    }

    private static function testResponse(): void {
      echo "<h3> - Creazione della Response tramite costruttore - </h3>";
      $response = new Response(true, "Success", null, "/api/test", false, []);
      try {
        Assertion::assertInstanceOf('Response', $response);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Response)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Response) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Creazione della response tramite builder - </h3>";
      $builder = new ResponseBuilder();
      $response = $builder
        ->withSuccess(true)
        ->withMessage("Built successfully")
        ->withEndpoint("/api/built")
        ->build();

      try {
        Assertion::assertInstanceOf('Response', $response);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Response)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Response) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testBuilderMethods(): void {
      $builder = new ResponseBuilder();

      echo "<h3> - Test di withSuccess() - </h3>";
      $builder1 = $builder->withSuccess(true);

      try {
        Assertion::assertInstanceOf('ResponseBuilder', $builder1);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(ResponseBuilder)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(ResponseBuilder) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test di withMessage() - </h3>";
      $builder2 = $builder->withMessage("Test message");

      try {
        Assertion::assertInstanceOf('ResponseBuilder', $builder2);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(ResponseBuilder)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(ResponseBuilder) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test di withErrorCode() - </h3>";
      $builder3 = $builder->withErrorCode("ERROR_123");

      try {
        Assertion::assertInstanceOf('ResponseBuilder', $builder3);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(ResponseBuilder)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(ResponseBuilder) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test di withEndpoint() - </h3>";
      $builder4 = $builder->withEndpoint("/api/endpoint");

      try {
        Assertion::assertInstanceOf('ResponseBuilder', $builder4);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(ResponseBuilder)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(ResponseBuilder) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test di withAuth() - </h3>";
      $builder5 = $builder->withAuth(true);

      try {
        Assertion::assertInstanceOf('ResponseBuilder', $builder5);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(ResponseBuilder)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(ResponseBuilder) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test di withData() - </h3>";
      $builder6 = $builder->withData(["key" => "value"]);

      try {
        Assertion::assertInstanceOf('ResponseBuilder', $builder6);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(ResponseBuilder)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(ResponseBuilder) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test dei metodi concatenati - </h3>";
      $response = $builder
        ->withSuccess(true)
        ->withMessage("Chained")
        ->withEndpoint("/api/chain")
        ->withAuth(false)
        ->withData(["chained" => true])
        ->build();

      try {
        Assertion::assertInstanceOf('Response', $response);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Response)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Response) " . $e->getMessage() . ".\n</p>";
      }

      try {
        Assertion::assertEquals('Chained', $response->getMessage());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Chained)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Chained) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testResponseProperties(): void {
      echo "<h3> - Test della proprieta': success - </h3>";
      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("Test")
        ->withEndpoint("/api/test")
        ->build();

      try {
        Assertion::assertTrue($response->getSuccess());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': message - </h3>";
      try {
        Assertion::assertEquals('Test', $response->getMessage());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(response->getMessage)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(response->getMessage) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': endpoint - </h3>";
      try {
        Assertion::assertEquals('/api/test', $response->getEndpoint());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(response->getEndpoint)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(response->getEndpoint) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': auth (false) - </h3>";
      try {
        Assertion::assertFalse($response->requiresAuth());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(response->requiresAuth)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(response->requiresAuth) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': auth (true) - </h3>";
      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("Auth test")
        ->withEndpoint("/api/auth")
        ->withAuth(true)
        ->build();

      try {
        Assertion::assertTrue($response->requiresAuth());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->requiresAuth)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->requiresAuth) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': error_code (null) - </h3>";
      try {
        Assertion::assertNull($response->getErrorCode());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull(response->getErrorCode)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNull(response->getErrorCode) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': error_code (!null) - </h3>";
      $response = (new ResponseBuilder())
        ->withSuccess(false)
        ->withMessage("Error")
        ->withEndpoint("/api/error")
        ->withErrorCode("VALIDATION_ERROR")
        ->build();

      try {
        Assertion::assertEquals('VALIDATION_ERROR', $response->getErrorCode());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(response->getErrorCode)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(response->getErrorCode) " . $e->getMessage() . ".\n</p>";
      }

      echo "<h3> - Test della proprieta': data - </h3>";
      $testData = ["user_id" => 123, "name" => "John"];
      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("Data test")
        ->withEndpoint("/api/data")
        ->withData($testData)
        ->build();

      try {
        Assertion::assertEquals($testData, $response->getData());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(response->getData)\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(response->getData) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testJsonConversion(): void {
      echo "  - Test della conversione in JSON - ";
      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("JSON test")
        ->withEndpoint("/api/json")
        ->withAuth(false)
        ->build();

      $json = $response->convertToJson();
      $decoded = json_decode($json, true);

      try {
        Assertion::assertTrue(is_string($json));
        Assertion::assertTrue(is_array($decoded));
        Assertion::assertTrue($decoded['success']);
        Assertion::assertEquals("JSON test", $decoded['message']);
        Assertion::assertEquals("/api/json", $decoded['endpoint']);
        Assertion::assertFalse($decoded['auth']);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(is_string(json_response))\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(is_array(decoded_json))\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(decoded_json[success])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(is_string(decoded_json[message]))\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(is_array(decoded_json[endpoint]))\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(decoded_json[auth])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(is_string(json_response)) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(is_array(decoded_json)) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(decoded_json[success]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(is_string(decoded_json[message])) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(is_array(decoded_json[endpoint])) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(decoded_json[auth]) " . $e->getMessage() . ".\n</p>";
      }

      echo "  - Test della conversione in JSON con: error_code - ";
      $response = (new ResponseBuilder())
        ->withSuccess(false)
        ->withMessage("Error occurred")
        ->withEndpoint("/api/error")
        ->withErrorCode("AUTH_FAILED")
        ->build();

      $json = $response->convertToJson();
      $decoded = json_decode($json, true);

      try{
        Assertion::assertTrue(isset($decoded['error_code']));
        Assertion::assertEquals('AUTH_FAILED', $decoded['error_code']);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(decoded_json[error_code])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[error_code])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(decoded_json[error_code]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[error_code]) " . $e->getMessage() . ".\n</p>";
      }

      echo "  - Test della conversione in JSON con: data - ";
      $testData = ["items" => [1, 2, 3], "total" => 3];
      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("Data loaded")
        ->withEndpoint("/api/items")
        ->withData($testData)
        ->build();

      $json = $response->convertToJson();
      $decoded = json_decode($json, true);

      try{
        Assertion::assertTrue(isset($decoded['data']));
        Assertion::assertEquals($testData, $decoded['data']);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(decoded_json[data])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[data])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(decoded_json[data]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[data]) " . $e->getMessage() . ".\n</p>";
      }

      echo "  - Test della conversione in JSON senza: error_code - ";
      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("No error")
        ->withEndpoint("/api/success")
        ->build();

      $json = $response->convertToJson();
      $decoded = json_decode($json, true);

      try{
        Assertion::assertFalse(isset($decoded['error_code']));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(decoded_json[error_code])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(decoded_json[error_code]) " . $e->getMessage() . ".\n</p>";
      }

      echo "  - Testing JSON without data - ";
      try{
        Assertion::assertFalse(isset($decoded['data']));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(decoded_json[data])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(decoded_json[data]) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testErrorHandling(): void {
      echo "  - Test della conversione in JSON con encoding fallito - ";
      // Creo un dato che non puo' essere codificato in JSON (riferimento circolare)
      $data = [];
      $data['self'] = &$data;

      $response = new Response(true, "Test", null, "/api/test", false, $data);
      $json = $response->convertToJson();

      $decoded = json_decode($json, true);
      try{
        Assertion::assertFalse($decoded['success']);
        Assertion::assertEquals("JSON encoding failed", $decoded['message']);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(decoded_json[success])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[message])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(decoded_json[success]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[message]) " . $e->getMessage() . ".\n</p>";
      }
    }

    private static function testBuilderValidation(): void {
      echo "  - Test di builder senza endpoint - ";
      $builder = new ResponseBuilder();
      $builder->withSuccess(true)->withMessage("Test");

      try {
        $builder->build();
        echo "Dovrebbe lanciare un'eccezione<br />";
        exit(1);
      } catch (Exception $e) {
        Assertion::assertEquals('Endpoint richiesto dalla Response', $e->getMessage());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Endpoint richiesto dalla Response)\n</p>";
      }

      echo "  - Test di builder con endpoint vuoto - ";
      $builder = new ResponseBuilder();
      $builder->withSuccess(true)->withMessage("Test")->withEndpoint("");

      try {
        $builder->build();
        echo "FAIL - Should have thrown exception<br />";
        exit(1);
      } catch (Exception $e) {
        Assertion::assertEquals('Endpoint richiesto dalla Response', $e->getMessage());
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Endpoint richiesto dalla Response)\n</p>";
      }
    }

    private static function testComplexResponses(): void {
      echo "  - Test di Response complessa con successo - ";
      $complexData = [
        "user" => [
          "id" => 123,
          "name" => "Pinco Pallino",
          "email" => "pinco@esempio.com",
          "roles" => ["user", "admin"]
        ],
        "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9",
        "expires_in" => 3600
      ];

      $response = (new ResponseBuilder())
        ->withSuccess(true)
        ->withMessage("Utente autenticato con successo")
        ->withEndpoint("/api/login")
        ->withAuth(true)
        ->withData($complexData)
        ->build();

      $json = $response->convertToJson();
      $decoded = json_decode($json, true);

      try{
        Assertion::assertTrue($decoded['success']);
        Assertion::assertEquals("Utente autenticato con successo", $decoded['message']);
        Assertion::assertEquals("/api/login", $decoded['endpoint']);
        Assertion::assertTrue($decoded['auth']);
        Assertion::assertEquals($complexData, $decoded['data']);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(decoded_json[success])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[message])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[endpoint])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(decoded_json[auth])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[data])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(decoded_json[success]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[message]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[endpoint]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(decoded_json[auth]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[data]) " . $e->getMessage() . ".\n</p>";
      }

      echo "  - Test di Response complessa con errore - ";
      $response = (new ResponseBuilder())
        ->withSuccess(false)
        ->withMessage("Multiple validation errors occurred")
        ->withEndpoint("/api/register")
        ->withErrorCode("VALIDATION_ERRORS")
        ->withData([
          "errors" => [
            "email" => "Formato email non valido",
            "password" => "La password deve essere di almeno 8 caratteri",
            "username" => "Username gia' in uso"
          ]
        ])
        ->build();

      $json = $response->convertToJson();
      $decoded = json_decode($json, true);

      try{
        Assertion::assertFalse($decoded['success']);
        Assertion::assertEquals("VALIDATION_ERRORS", $decoded['error_code']);
        Assertion::assertTrue(isset($decoded['data']['errors']));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(decoded_json[success])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(decoded_json[error_code])\n</p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(decoded_json[data])\n</p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(decoded_json[success]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(decoded_json[error_code]) " . $e->getMessage() . ".\n</p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(decoded_json[data]) " . $e->getMessage() . ".\n</p>";
      }
    }
  }

  // Run tests if executed directly
  if (basename($_SERVER['PHP_SELF']) === 'ResponseTest.php') {
    try {
      ResponseTest::runAll();
    } catch (Exception $e) {
      echo "<br />TEST FAILED: " . $e->getMessage() . "<br />";
      exit(1);
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResponseTest</title>
  <link rel="stylesheet" href="testStyle.css">
</head>
<body>
</body>
</html>