<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Nested;

use Drupal\pinto\Object\DrupalObjectTrait;
use Pinto\Attribute\ThemeDefinition;

/**
 * Test object.
 */
final class ObjectNestedViaVariable {

  use DrupalObjectTrait;

  /**
   * Constructor.
   *
   * @phpstan-param callable-object $object
   */
  private function __construct(
    readonly object $object,
  ) {
  }

  /**
   * Creates a new object.
   *
   * @phpstan-param callable-object $object
   */
  public static function create(
    object $object,
  ): static {
    return new static($object);
  }

  public function __invoke(): mixed {
    return $this->pintoBuild(function (mixed $build): mixed {
      return $build + [
        '#inner' => ($this->object)(),
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
