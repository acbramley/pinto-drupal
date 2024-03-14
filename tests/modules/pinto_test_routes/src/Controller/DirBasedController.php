<?php

declare(strict_types=1);

namespace Drupal\pinto_test_routes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pinto_test\Pinto\DirectoryBased\ObjectDirBased;

final class DirBasedController extends ControllerBase {

  public function __invoke(): array {
    return [
      'foo' => ObjectDirBased::create('foo bar!')->build(),
    ];
  }

}
