<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\DirectoryBased;

use Drupal\pinto\List\SingleDirectoryObjectListTrait;
use Pinto\Attribute\Definition;
use Pinto\List\ObjectListInterface;
use Pinto\List\ObjectListTrait;

/**
 * Defines objects where assets are organized into directories per object.
 */
enum DirectoryBased: string implements ObjectListInterface {

  use ObjectListTrait;
  use SingleDirectoryObjectListTrait;

  #[Definition(ObjectDirBased::class)]
  case Object_Dir_Based = 'object_dir_based';

  public function templateName(): string {
    return 'template';
  }

  private function directory(): string {
    return '@pinto_test/resources/' . $this->name;
  }

}
