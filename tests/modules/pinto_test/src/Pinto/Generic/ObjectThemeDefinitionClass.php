<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Generic;

use Pinto\Attribute\Build;
use Pinto\Attribute\ThemeDefinition;

#[ThemeDefinition(
  definition: [
    'variables' => [
      'foo' => NULL,
    ],
  ],
)]
final class ObjectThemeDefinitionClass {

  #[Build]
  public function build(): mixed {
    return [];
  }

}
