<?php

declare(strict_types=1);

namespace Drupal\pinto_test_routes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pinto_test\Pinto\Nested\ObjectNested;

final class NestedPredefinedController extends ControllerBase {

  public function __invoke(): array {
    return [
      'foo' => new ObjectNested(),
    ];
  }

}
