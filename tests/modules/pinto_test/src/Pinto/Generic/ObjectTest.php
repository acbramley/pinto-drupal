<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Generic;

use Drupal\pinto\Object\DrupalObjectTrait;
use Pinto\Attribute\Asset\Css;
use Pinto\Attribute\Asset\Js;
use Pinto\Attribute\ThemeDefinition;

/**
 * Test object.
 */
#[Css('styles.css')]
#[Js('app.js')]
final class ObjectTest {

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
        '#test_text' => $this->text,
      ];
    });
  }

  #[ThemeDefinition]
  public static function theme(): array {
    return [
      'variables' => [
        'test_text' => NULL,
      ],
    ];
  }

}
