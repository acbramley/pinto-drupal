<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\DirectoryBasedWithPhp;

use Drupal\pinto\List\SingleDirectoryObjectListTrait;
use Pinto\Attribute\Definition;
use Pinto\List\ObjectListInterface;
use Pinto\List\ObjectListTrait;
use PintoResources\pinto_test\Object_Dir_Based_With_Php\ThemeObject;

/**
 * Defines objects where assets are organized alongside the defining object.
 *
 * This strategy is similar to Single Directory Components (SDC).
 */
enum DirectoryBasedWithPhp: string implements ObjectListInterface {

  use ObjectListTrait;
  use SingleDirectoryObjectListTrait;

  #[Definition(ThemeObject::class)]
  case Object_Dir_Based_With_Php = 'object_dir_based_with_php';

  public function templateName(): string {
    return 'template';
  }

}
