<?php

  declare(strict_types = 1);

  // final class Assert {

  //   public static function equal(int $currentValue, int $expectedValue) {
  //     if ($currentValue !== $expectedValue) {
  //       throw new \RuntimeException("Values are not equal, current value is: {$currentValue} and was expected: {$expectedValue}");
  //     }
  //   }

  // }

class Assertion {

  /**
   * Assert that two values are equal
   *
   * @param mixed $expected
   * @param mixed $actual
   * @param string $message
   * @throws Exception
   */
  public static function assertEquals($expected, $actual, string $message = '') {
    if ($expected !== $actual) {
      throw new Exception($message ?: "Assert::assertEquals, expected: \"{$expected}\", but got \"{$actual}\" -> <strong>failed</strong>.");
    }
  }

  /**
   * Assert that a value is true
   *
   * @param mixed $value
   * @param string $message
   * @throws Exception
   */
  public static function assertTrue($value, string $message = '') {
    if ($value !== true) {
      throw new Exception($message ?: "Expected value to be true, but got false.");
    }
  }

  /**
   * Assert that a value is false
   *
   * @param mixed $value
   * @param string $message
   * @throws Exception
   */
  public static function assertFalse($value, string $message = '') {
    if ($value !== false) {
      throw new Exception($message ?: "Expected value to be false, but got '$value'.");
    }
  }

  /**
   * Assert that a value is null
   *
   * @param mixed $value
   * @param string $message
   * @throws Exception
   */
  public static function assertNull($value, string $message = '') {
    if ($value !== null) {
      throw new Exception($message ?: "Expected value to be null, but got '$value'.");
    }
  }

  /**
   * Assert that a value is not null
   *
   * @param mixed $value
   * @param string $message
   * @throws Exception
   */
  public static function assertNotNull($value, string $message = '') {
    if ($value === null) {
      throw new Exception($message ?: "Expected value to not be null.");
    }
  }

  /**
   * Assert that a condition is true
   *
   * @param bool $condition
   * @param string $message
   * @throws Exception
   */
  public static function assertCondition($condition, string $message = '') {
    if (!$condition) {
      throw new Exception($message ?: 'Condition failed.');
    }
  }

  /**
   * Assert that two values are equal
   *
   * @param array $expected
   * @param array $actual
   * @param string $message
   * @throws Exception
   */
  public static function assertArrayEquals(array $expected, array $actual, string $message = '') {
    if ($expected !== $actual) {
      throw new Exception($message ?: 'Arrays are not equal.');
    }
  }

  /**
     * Assert that an object is an instance of a given class
     *
     * @param string $expectedClass
     * @param object $actualObject
     * @param string $message
     * @throws Exception
     */
    public static function assertInstanceOf($expectedClass, $actualObject, string $message = '') {
      if (!is_object($actualObject)) {
        throw new Exception($message ? : "Expected an object, but got a non-object.");
      }

      if (!$actualObject instanceof $expectedClass) {
        throw new Exception($message ? : "Expected instance of {$$expectedClass}, but got instance of " . get_class($actualObject) . ".");
      }
    }

}

?>