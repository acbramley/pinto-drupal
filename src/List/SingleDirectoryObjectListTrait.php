<?php

declare(strict_types=1);

namespace Drupal\pinto\List;

use Pinto\Attribute\Definition;

/**
 * An implementation where all resources are in a single directory.
 *
 * @see \Pinto\List\ObjectListInterface
 */
trait SingleDirectoryObjectListTrait {

  public function templateDirectory(): string {
    return $this->directory();
  }

  public function cssDirectory(): string {
    return $this->directory();
  }

  public function jsDirectory(): string {
    return $this->directory();
  }

  private function directory(): string {
    // Gets the resources from the same directory as the object PHP file.
    // Override this method with a different directory if you want to keep
    // assets in different directories from object PHP files.
    $definition = ((new \ReflectionEnumUnitCase($this::class, $this->name))->getAttributes(Definition::class)[0] ?? NULL)?->newInstance() ?? throw new \LogicException('All component cases must have a `' . Definition::class . '`.');
    return SingleDirectoryObjectListCache::nsAndDir($definition->className);
  }

}
