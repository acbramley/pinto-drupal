<?php

declare(strict_types=1);

namespace Drupal\pinto_test_routes\Controller;

use Drupal\Core\Controller\ControllerBase;
use PintoResources\pinto_test\Object_Dir_Based_With_Php\ThemeObject;

final class DirBasedWithPhpController extends ControllerBase {

  public function __invoke(): array {
    return [
      'foo' => ThemeObject::create('foo bar!'),
    ];
  }

}
