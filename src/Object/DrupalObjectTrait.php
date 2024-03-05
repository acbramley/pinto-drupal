<?php

declare(strict_types=1);

namespace Drupal\pinto\Object;

use Pinto\Object\ObjectTrait;
use Pinto\PintoMapping;

trait DrupalObjectTrait {

  use ObjectTrait;

  /**
   * Use a mapping pre-made in the container.
   *
   * @internal
   */
  private function pintoMapping(): PintoMapping {
    return \Drupal::service(PintoMapping::class);
  }

}
