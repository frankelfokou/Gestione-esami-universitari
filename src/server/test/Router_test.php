<?php
  ini_set('zend.assertions', 1);  // Enable assertions, set to -1 in production
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Router test</title>
</head>
<body>
  <h3>Testing Router</h3>
  <?php
    $something = "poop";
    assert($something === 1, "This code should fail");
  ?>
</body>
</html>