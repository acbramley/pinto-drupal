<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Generic;

use Pinto\Attribute\Build;

/**
 * Test object.
 *
 * Throws an exception when build is called.
 */
final class ObjectWithoutListMapping {

  #[Build]
  public function build(): mixed {
    return [];
  }

}
