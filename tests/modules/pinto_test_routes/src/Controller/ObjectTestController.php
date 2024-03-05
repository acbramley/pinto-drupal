<?php

declare(strict_types=1);

namespace Drupal\pinto_test_routes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pinto_test\Pinto\Generic\ObjectTest;

final class ObjectTestController extends ControllerBase {

  public function __invoke(): array {
    return [
      // Direct invoke.
      // Avoids invocation via Renderer.
      'foo' => ObjectTest::create('foo bar!')(),
    ];
  }

}
