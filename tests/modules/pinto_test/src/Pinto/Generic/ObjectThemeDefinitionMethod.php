<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Generic;

use Drupal\pinto\Object\DrupalObjectTrait;
use Pinto\Attribute\Build;
use Pinto\Attribute\ThemeDefinition;

final class ObjectThemeDefinitionMethod {

  use DrupalObjectTrait;

  #[Build]
  public function build(): mixed {
    return [];
  }

  #[ThemeDefinition]
  public static function themeDef(): array {
    return [
      'variables' => [
        'foo' => NULL,
      ],
    ];
  }

}
