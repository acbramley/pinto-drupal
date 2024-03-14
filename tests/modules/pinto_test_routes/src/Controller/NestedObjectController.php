<?php

declare(strict_types=1);

namespace Drupal\pinto_test_routes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pinto_test\Pinto\Nested\ObjectNestedInner;
use Drupal\pinto_test\Pinto\Nested\ObjectNestedViaVariable;

final class NestedObjectController extends ControllerBase {

  public function __invoke(): array {
    return [
      'foo' => ObjectNestedViaVariable::create(
        ObjectNestedInner::create('Nested inner text in a nested variable!'),
      )(),
    ];
  }

}
