<?php

declare(strict_types=1);

namespace PintoResources\pinto_test\Object_Dir_Based_With_Php;

use Drupal\pinto\Object\DrupalObjectTrait;
use Pinto\Attribute\Asset\Css;
use Pinto\Attribute\Asset\Js;
use Pinto\Attribute\Build;
use Pinto\Attribute\ThemeDefinition;

/**
 * Test object.
 */
#[Css('styles.css')]
#[Js('app.js')]
final class ThemeObject {

  use DrupalObjectTrait;

  private function __construct(
    readonly string $text,
  ) {
  }

  public static function create(
    string $text,
  ): static {
    return new static($text);
  }

  #[Build]
  public function build(): mixed {
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
