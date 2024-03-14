<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Nested;

use Drupal\pinto\Object\DrupalObjectTrait;
use Pinto\Attribute\ThemeDefinition;

/**
 * Test object.
 */
final class ObjectNested {

  use DrupalObjectTrait;

  public function __invoke(): mixed {
    return $this->pintoBuild(function (mixed $build): mixed {
      return $build + [
        '#inner' => ObjectNestedInner::create('Nested inner text!')(),
      ];
    });
  }

  #[ThemeDefinition]
  public static function theme(): array {
    return [
      'variables' => [
        'inner' => NULL,
      ],
    ];
  }

}
