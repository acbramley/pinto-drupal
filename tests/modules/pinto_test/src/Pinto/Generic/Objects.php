<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Generic;

use Pinto\Attribute\Definition;
use Pinto\List\ObjectListInterface;
use Pinto\List\ObjectListTrait;
use function Safe\realpath;

/**
 * Defines objects where assets are organized in traditional locations.
 *
 * Where JS and CSS are located in their own directories.
 */
enum Objects: string implements ObjectListInterface {

  use ObjectListTrait;

  #[Definition(ObjectTest::class)]
  case ObjectTest = 'object_test';

  #[Definition(ObjectThemeDefinitionClass::class)]
  case ObjectThemeDefinitionClass = 'theme_definition_on_class';

  #[Definition(ObjectThemeDefinitionMethod::class)]
  case ObjectThemeDefinitionMethod = 'theme_definition_on_method';

  public function templateDirectory(): string {
    return '@pinto_test/templates/';
  }

  public function cssDirectory(): string {
    return substr(realpath(__DIR__ . '/../../../css'), \strlen(\DRUPAL_ROOT));
  }

  public function jsDirectory(): string {
    return substr(realpath(__DIR__ . '/../../../js'), \strlen(\DRUPAL_ROOT));
  }

}
