<?php

  declare(strict_types = 1);

  interface CommandInterface {
    public function execute(Request $request): Response;
  }

?>