<?php

declare(strict_types=1);

namespace Drupal\pinto;

use Pinto\Attribute\Build;
use Pinto\Attribute\Definition;
use Pinto\Attribute\ThemeDefinition;
use Pinto\List\ObjectListInterface;
use Pinto\PintoMapping;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Pinto compiler pass.
 *
 * Searches for Pinto enums in defined namespaces, then records the classnames
 * to the container so they may be efficiently fetched at runtime.
 */
final class PintoCompilerPass implements CompilerPassInterface {

  /**
   * {@inheritdoc}
   */
  public function process(ContainerBuilder $container): void {
    /** @var array<class-string, string> $containerNamespaces */
    $containerNamespaces = $container->getParameter('container.namespaces');

    /** @var string[] $pintoNamespaces */
    $pintoNamespaces = $container->getParameter('pinto.namespaces');

    $enumClasses = [];
    $enums = [];
    $themeDefinitions = [];
    $buildInvokers = [];

    foreach ($this->getEnums($containerNamespaces, $pintoNamespaces) as $r) {
      /** @var class-string<\Pinto\List\ObjectListInterface> $objectListClassName */
      $objectListClassName = $r->getName();
      $enumClasses[] = $objectListClassName;

      foreach ($r->getReflectionConstants() as $constant) {
        /** @var array<\ReflectionAttribute<\Pinto\Attribute\Definition>> $attributes */
        $attributes = $constant->getAttributes(Definition::class);
        $definition = ($attributes[0] ?? NULL)?->newInstance();
        if ($definition === NULL) {
          continue;
        }

        if ($container->hasDefinition($definition->className)) {
          // Ignore when this is a service.
          continue;
        }

        $themeDefinitions[$definition->className] = ThemeDefinition::themeDefinitionForThemeObject($definition->className);
        $buildInvokers[$definition->className] = Build::buildMethodForThemeObject($definition->className);
        $enums[$definition->className] = [$objectListClassName, $constant->getName()];
      }
    }

    $container->getDefinition(PintoMapping::class)
      // $enumClasses is a separate parameter since there may be zero $enums.
      ->setArgument('$enumClasses', $enumClasses)
      ->setArgument('$enums', $enums)
      ->setArgument('$themeDefinitions', $themeDefinitions)
      ->setArgument('$buildInvokers', $buildInvokers);
  }

  /**
   * Get enums for the provided namespaces.
   *
   * @param array<class-string, string> $namespaces
   *   An array of namespaces. Where keys are class strings and values are
   *   paths.
   * @param string[] $pintoNamespaces
   *   Pinto namespaces.
   *
   * @return \Generator<\ReflectionClass<\Pinto\List\ObjectListInterface>>
   *   Generates class strings.
   *
   * @throws \ReflectionException
   */
  private function getEnums(array $namespaces, array $pintoNamespaces): \Generator {
    foreach ($namespaces as $namespace => $dirs) {
      $dirs = (array) $dirs;
      foreach ($dirs as $dir) {
        foreach ($pintoNamespaces as $pintoNamespace) {
          $dir .= '/' . $pintoNamespace;
          if (!file_exists($dir)) {
            continue;
          }
          $namespace .= '\\' . $pintoNamespace;

          /** @var \RecursiveIteratorIterator<\RecursiveDirectoryIterator<\SplFileInfo>> $iterator */
          $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
          foreach ($iterator as $fileinfo) {
            assert($fileinfo instanceof \SplFileInfo);
            if ('php' !== $fileinfo->getExtension()) {
              continue;
            }

            /** @var \RecursiveDirectoryIterator|null $subDir */
            $subDir = $iterator->getSubIterator();
            if (NULL === $subDir) {
              continue;
            }

            $subDir = $subDir->getSubPath();
            $subDir = $subDir !== '' ? str_replace(DIRECTORY_SEPARATOR, '\\', $subDir) . '\\' : '';

            /** @var class-string $class */
            $class = $namespace . '\\' . $subDir . $fileinfo->getBasename('.php');

            /** @var \ReflectionClass<\Pinto\List\ObjectListInterface> $reflectionClass */
            $reflectionClass = new \ReflectionClass($class);
            if ($reflectionClass->isEnum() && $reflectionClass->implementsInterface(ObjectListInterface::class)) {
              yield $reflectionClass;
            }
          }
        }
      }
    }
  }

}
