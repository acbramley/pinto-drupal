<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Nested;

use Drupal\pinto\Object\DrupalObjectTrait;
use Pinto\Attribute\ThemeDefinition;

/**
 * Test object.
 */
final class ObjectNestedInner {

  use DrupalObjectTrait;

  /**
   * Constructor.
   */
  private function __construct(
    readonly string $text,
  ) {
  }

  /**
   * Creates a new object.
   */
  public static function create(
    string $text,
  ): static {
    return new static($text);
  }

  public function __invoke(): mixed {
    return $this->pintoBuild(function (mixed $build): mixed {
      return $build + [
        '#foo' => $this->text,
      ];
    });
  }

  #[ThemeDefinition]
  public static function theme(): array {
    return [
      'variables' => [
        'foo' => NULL,
      ],
    ];
  }

}
