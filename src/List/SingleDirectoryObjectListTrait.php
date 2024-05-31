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
    return $this->twigDirectory();
  }

  public function cssDirectory(): string {
    return $this->libraryDirectory();
  }

  public function jsDirectory(): string {
    return $this->libraryDirectory();
  }

  /**
   * May be extended.
   */
  protected function twigDirectory(): string {
    // Gets the resources from the same directory as the object PHP file.
    // Override this method with a different directory if you want to keep
    // assets in different directories from object PHP files.
    $definition = ((new \ReflectionEnumUnitCase($this::class, $this->name))->getAttributes(Definition::class)[0] ?? NULL)?->newInstance() ?? throw new \LogicException('All component cases must have a `' . Definition::class . '`.');
    return SingleDirectoryObjectListCache::twigNsAndDir($definition->className);
  }

  /**
   * May be extended.
   */
  protected function libraryDirectory(): string {
    // Gets the resources from the same directory as the object PHP file.
    // Override this method with a different directory if you want to keep
    // assets in different directories from object PHP files.
    $definition = ((new \ReflectionEnumUnitCase($this::class, $this->name))->getAttributes(Definition::class)[0] ?? NULL)?->newInstance() ?? throw new \LogicException('All component cases must have a `' . Definition::class . '`.');
    return SingleDirectoryObjectListCache::libraryDir($definition->className);
  }

}
