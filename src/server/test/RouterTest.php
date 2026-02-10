<?php

  declare(strict_types = 1);

  require_once(__DIR__. '/Assertion.php');
  require_once(dirname(__FILE__, 2) . '/Request.php');
  require_once(dirname(__FILE__, 2) . '/Response.php');
  require_once(dirname(__FILE__, 2) . '/Router.php');

  require_once(dirname(__FILE__, 2) . '/api/commands/CommandInterface.php');
  require_once(dirname(__FILE__, 2) . '/api/commands/LoginCommand.php');
  require_once(dirname(__FILE__, 2) . '/api/commands/LogoutCommand.php');
  require_once(dirname(__FILE__, 2) . '/api/commands/RegisterCommand.php');
  require_once(dirname(__FILE__, 2) . '/api/commands/ExamsCommand.php');
  require_once(dirname(__FILE__, 2) . '/api/commands/StatsCommand.php');

  // Mock functions per il testing
  function loginEP(): array {
    return [
      'success' => true,
      'message' => 'Login effetuato con successo',
      'user_id' => 123,
      'token' => 'afipoaj123q4pj',
      'expires_in' => 3600
    ];
  }

  function registerEP(): array {
    return [
      'success' => true,
      'message' => 'Registrazione completata',
      'user_id' => 456,
      'email' => 'test@esempio.com'
    ];
  }

  function examsEP(): array {
    return [
      'success' => true,
      'message' => 'Esami recuperati con successo',
      'exams' => [
        ['id' => 1, 'name' => 'Analisi_matematica', 'date' => '2024-06-15', 'score' => 85],
        ['id' => 2, 'name' => 'Architettura_degli_elaboratori', 'date' => '2024-06-20', 'score' => 92]
      ],
      'total' => 2,
      'average_score' => 88.5
    ];
  }

  function statsEP(): array {
    return [
      'success' => true,
      'message' => 'Statistiche caricate',
      'total_exams' => 15,
      'passed_exams' => 12,
      'failed_exams' => 3,
      'pass_rate' => 80.0,
      'average_score' => 78.5
    ];
  }

  function logoutEP(): array {
    return [
      'success' => true,
      'message' => 'Logout effetuato con successo'
    ];
  }

  class RouterTest {
    public static function runAll(): void {
      echo "<h2>Test della classe Router con design pattern Command</h2><br />";

      echo "<h4 class=\"test_name\">1. Test dell'inizializzazione del Router</h4><br />";
      self::testRouterInitialization();

      echo "<h4 class=\"test_name\">2. Test del controllo delle route</h4><br />";
      self::testRouteChecking();

      echo "<h4 class=\"test_name\">3. Test del dispatcher</h4><br />";
      self::testRouteDispatch();

      echo "<h4 class=\"test_name\">4. Test della validazione dei metodi HTTP</h4><br />";
      self::testMethodValidation();

      echo "<h4 class=\"test_name\">5. Test della mappatura degli Uri</h4><br />";
      self::testUriToRouteMapping();

      echo "<h4 class=\"test_name\">6. Test della gestione degli errori</h4><br />";
      self::testErrorHandling();

      echo "<h4 class=\"test_name\">7. Test dell'implementazione del design pattern Command</h4><br />";
      self::testCommandPattern();

      echo "<h4 class=\"test_name\">8. Test completo della gestione delle Request</h4><br />";
      self::testCompleteRequestHandling();
    }

    private static function createTestRouter(): Router {
      return new class() extends Router {
        public function __construct() {
          // Non invocare costruttore della classe padre per evitare il caricamento da file
          // Setup manuale delle router per il test
          $this->routes = [
            'login' => [
              'method' => 'POST',
              'command' => 'LoginCommand'
            ],
            'register' => [
              'method' => 'POST',
              'command' => 'RegisterCommand'
            ],
            'exams' => [
              'method' => 'GET',
              'command' => 'ExamsCommand'
            ],
            'stats' => [
              'method' => 'GET',
              'command' => 'StatsCommand'
            ],
            'logout' => [
              'method' => 'POST',
              'command' => 'LogoutCommand'
            ]
          ];
        }
      };
    }

    private static function testRouterInitialization(): void {
      echo "<h3> - Creazione dell'istanza di Router' - </h3>";

      $router = self::createTestRouter();

      try {
        Assertion::assertInstanceOf('Router', $router);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Router)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Router) " . $e->getMessage() . ".<br /></p>";
      }

      try {
        Assertion::assertNotNull($router);
        echo "<p class=\"success\"><strong>PASSED</strong>: assertNotNull(Router)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNotNull(Router) " . $e->getMessage() . ".<br /></p>";
      }
    }

    private static function testRouteChecking(): void {
      $router = self::createTestRouter();

      echo "<h3> - Verifica della route login - </h3>";
      try {
        Assertion::assertTrue($router->checkRoute('login'));
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(router->checkRoute[login])<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(router->checkRoute[login]) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Verifica della route esami - </h3>";
      try {
        Assertion::assertTrue($router->checkRoute('exams'));
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(router->checkRoute[esami])<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(router->checkRoute[esami]) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Verifica della route stats - </h3>";
      try {
        Assertion::assertTrue($router->checkRoute('stats'));
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(router->checkRoute[stats])<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(router->checkRoute[stats]) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Verifica di route non esistente - </h3>";
      try {
        Assertion::assertFalse($router->checkRoute('nonexistent'));
        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(router->checkRoute[nonexistent])<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(router->checkRoute[nonexistent]) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Verifica del case sensitive - </h3>";
      try {
        Assertion::assertFalse($router->checkRoute('LOGIN'));
        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(router->checkRoute[LOGIN])<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(router->checkRoute[LOGIN]) " . $e->getMessage() . ".<br /></p>";
      }
    }
    
    private static function testRouteDispatch(): void {
      $router = self::createTestRouter();

      echo "<h3> - Dispatch della route login' - </h3>";

      $request = new Request('POST', '/login', [], [
        'username' => 'testuser',
        'password' => 'testpass'
      ]);

      $response = $router->dispatch('login', $request);

      try {
        Assertion::assertInstanceOf('Response', $response);
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('Login effetuato con successo', $response->getMessage());
        Assertion::assertEquals('login', $response->getEndpoint());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Response)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Login effetuato con successo)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(login)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Response) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Login effetuato con successo) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(login) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Dispatch della route esami' - </h3>";

      $request = new Request('GET', '/exams');
      $response = $router->dispatch('exams', $request);

      try {
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('Esami recuperati con successo', $response->getMessage());
        Assertion::assertEquals('exams', $response->getEndpoint());
        Assertion::assertTrue($response->requiresAuth());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Esami recuperati con successo)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(exams)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->requiresAuth)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Esami recuperati con successo) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(exams) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->requiresAuth) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Dispatch della route register' - </h3>";

      $request = new Request('POST', '/register', [], [
        'email' => 'new@example.com',
        'password' => 'secret123'
      ]);

      $response = $router->dispatch('register', $request);

      try {
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('Registrazione completata', $response->getMessage());
        Assertion::assertEquals('register', $response->getEndpoint());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Registrazione completata)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(register)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Registrazione completata) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(register) " . $e->getMessage() . ".<br /></p>";
      }
    }
    
    private static function testMethodValidation(): void {
      $router = self::createTestRouter();

      echo "<h3> - Test con metodo sbagliato su login (GET invece che POST) - </h3>";

      $request = new Request('GET', '/login');
      $response = $router->dispatch('login', $request);

      try {
        Assertion::assertFalse($response->getSuccess());
        Assertion::assertEquals('METODO_NON_CONSENTITO', $response->getErrorCode());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(METODO_NON_CONSENTITO)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(METODO_NON_CONSENTITO) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Test con metodo sbagliato su esami (GET invece che POST) - </h3>";
      $request = new Request('POST', '/exams');
      $response = $router->dispatch('exams', $request);

      try {
        Assertion::assertFalse($response->getSuccess());
        Assertion::assertEquals('METODO_NON_CONSENTITO', $response->getErrorCode());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(METODO_NON_CONSENTITO)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(METODO_NON_CONSENTITO) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Test con metodo corretto su login - </h3>";

      $request = new Request('POST', '/login');
      $response = $router->dispatch('login', $request);

      try {
        Assertion::assertTrue($response->getSuccess());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
      }
    }
    
    private static function testUriToRouteMapping(): void {
      $router = self::createTestRouter();

      echo "<h3> - Mapping dell'URI su nome route (semplice) - </h3>";

      $routeName = $router->getRouteFromUri('login');

      try {
        Assertion::assertEquals('login', $routeName);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(login)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(login) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Mapping dell'URI su nome route (con slash / all'inizio) - </h3>";

      $routeName = $router->getRouteFromUri('/login');

      try {
        Assertion::assertEquals('login', $routeName);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(login)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(login) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Mapping dell'URI su nome route (con slash / alla fine) - </h3>";

      $routeName = $router->getRouteFromUri('exams/');

      try {
        Assertion::assertEquals('exams', $routeName);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(exams)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(exams) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Mapping di un URI non esistente - </h3>";

      $routeName = $router->getRouteFromUri('nonexistent');

      try {
        Assertion::assertNull($routeName);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull(nonexistent)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNull(nonexistent) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Mapping di un URI vuoto - </h3>";

      $routeName = $router->getRouteFromUri('');

      try {
        Assertion::assertNull($routeName);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull()<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNull() " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Mapping di un URI root (/) - </h3>";

      $routeName = $router->getRouteFromUri('/');

      try {
        Assertion::assertNull($routeName);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertNull(/)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertNull(/) " . $e->getMessage() . ".<br /></p>";
      }
    }

    private static function testErrorHandling(): void {
      $router = self::createTestRouter();

      echo "<h3> - Dispatch di una route non esistente - </h3>";

      $request = new Request('GET', '/nonexistent');
      $response = $router->dispatch('nonexistent', $request);

      try {
        Assertion::assertFalse($response->getSuccess());
        Assertion::assertEquals('Route non trovata', $response->getMessage());
        Assertion::assertEquals('ROUTE_NON_TROVATA', $response->getErrorCode());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Route non trovata)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(ROUTE_NON_TROVATA)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Route non trovata) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(ROUTE_NON_TROVATA) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Test di un command mancante - </h3>";

      $router = new class() extends Router {
        public function __construct() {
          $this->routes = [
            'test' => [
              'method' => 'GET',
              'command' => 'NonExistentCommand'
            ]
          ];
        }

        protected function loadRoutesFromFile(string $filePath): void {
          // Override
        }
      };

      $request = new Request('GET', '/test');
      $response = $router->dispatch('test', $request);

      try {
        Assertion::assertFalse($response->getSuccess());
        Assertion::assertEquals('Command non trovato', $response->getMessage());
        Assertion::assertEquals('COMMAND_NON_TROVATO', $response->getErrorCode());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(Command non trovato)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(COMMAND_NON_TROVATO)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(Command non trovato) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(COMMAND_NON_TROVATO) " . $e->getMessage() . ".<br /></p>";
      }
    }

    private static function testCommandPattern(): void {
      echo "<h3> - Test esecuzione del command di login - </h3>";

      $command = new LoginCommand();
      $request = new Request('POST', '/login', [], [
        'username' => 'test',
        'password' => 'test'
      ]);
      $response = $command->execute($request);

      try {
        Assertion::assertInstanceOf('Response', $response);
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('login', $response->getEndpoint());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Response)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(login)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Response) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(login) " . $e->getMessage() . ".<br /></p>";
      }

      $data = $response->getData();

      try {
        Assertion::assertTrue(isset($data['user_id']));
        Assertion::assertTrue(isset($data['token']));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(data[user_id])<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(data[token])<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(data[user_id]) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(data[token]) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Test esecuzione del command di exams - </h3>";

      $command = new ExamsCommand();
      $request = new Request('GET', '/exams');
      $response = $command->execute($request);

      try {
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('exams', $response->getEndpoint());
        Assertion::assertTrue($response->requiresAuth());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(exams)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->requiresAuth)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(exams) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->requiresAuth) " . $e->getMessage() . ".<br /></p>";
      }

      $data = $response->getData();

      try {
        Assertion::assertTrue(isset($data['exams']));
        Assertion::assertTrue(is_array($data['exams']));

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(data[exams] - isset)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(data[exams] - is_array)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(data[exams] - isset) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(data[exams] - is_array) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Test di implementazione di CommandInterface - </h3>";

      try {
        Assertion::assertInstanceOf('CommandInterface', $command);

        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(CommandInterface)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(CommandInterface) " . $e->getMessage() . ".<br /></p>";
      }
    }

    private static function testCompleteRequestHandling(): void {
      $router = self::createTestRouter();

      echo "<h3> - Gestione completa della request di login - </h3>";

      $request = new Request('POST', '/login', [], [
        'username' => 'student1',
        'password' => 'password123'
      ]);
      $response = $router->handleRequest($request);

      try {
        Assertion::assertInstanceOf('Response', $response);
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('login', $response->getEndpoint());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertInstanceOf(Response)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(login)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertInstanceOf(Response) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(login) " . $e->getMessage() . ".<br /></p>";
      }


      echo "<h3> - Gestione completa della request di exams - </h3>";

      $request = new Request('GET', '/exams');
      $response = $router->handleRequest($request);

      try {
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('exams', $response->getEndpoint());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(exams)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(exams) " . $e->getMessage() . ".<br /></p>";
      }

      echo "<h3> - Gestione della request di route non esistenti - </h3>";

      $request = new Request('GET', '/api/nonexistent');
      $response = $router->handleRequest($request);

      try {
        Assertion::assertFalse($response->getSuccess());
        Assertion::assertEquals('ROUTE_NON_TROVATA', $response->getErrorCode());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertFalse(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(ROUTE_NON_TROVATA)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertFalse(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(ROUTE_NON_TROVATA) " . $e->getMessage() . ".<br /></p>";
      }
      

      echo "<h3> - Gestione delle request parametri - </h3>";

      $request = new Request('GET', '/stats?year=2024&subject=Analisi_matematica');
      $response = $router->handleRequest($request);

      try {
        Assertion::assertTrue($response->getSuccess());
        Assertion::assertEquals('stats', $response->getEndpoint());

        echo "<p class=\"success\"><strong>PASSED</strong>: assertTrue(response->getSuccess)<br /></p>";
        echo "<p class=\"success\"><strong>PASSED</strong>: assertEquals(stats)<br /></p>";
      }
      catch(Exception $e) {
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertTrue(response->getSuccess) " . $e->getMessage() . ".<br /></p>";
        echo "<p class=\"fail\"><strong>FAILED</strong>: assertEquals(stats) " . $e->getMessage() . ".<br /></p>";
      }
    }
  }

  // Run tests if executed directly
  if (basename($_SERVER['PHP_SELF']) === 'RouterTest.php') {
    try {
      RouterTest::runAll();
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
  <title>RouterTest</title>
  <link rel="stylesheet" href="testStyle.css">
</head>
<body>
</body>
</html>