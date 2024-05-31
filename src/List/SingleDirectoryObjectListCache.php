<?php

declare(strict_types=1);

namespace Drupal\pinto\List;

/**
 * @internal
 */
final class SingleDirectoryObjectListCache {

  /**
   * The nearest module root.
   *
   * Internal, may be removed at any time.
   *
   * Since some installations, like DrupalCI, may use symlinks, and __DIR__
   * always uses the resolved dir. So cant use the dir, just use the
   * "@-modulename" form for Twig. Alternatively if this doesn't work out, use
   * a custom \Twig\Loader\LoaderInterface.
   *
   * @internal
   */
  private static array $nsAndDir = [];

  /**
   * The app-relative directory of an object class.
   *
   * Internal, may be removed at any time.
   *
   * @internal
   */
  private static array $libraryDir = [];

  /**
   * @phpstan-param class-string $objectClassName
   */
  public static function twigNsAndDir(string $objectClassName): string {
    if (isset(static::$nsAndDir[$objectClassName])) {
      return static::$nsAndDir[$objectClassName];
    }

    /** @var string $fileName */
    $fileName = (new \ReflectionClass($objectClassName))->getFileName();
    $objectClassDir = dirname($fileName);
    $dir = $objectClassDir;
    while (TRUE) {
      $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
      /** @var \SplFileInfo[] $iterator */
      foreach ($iterator as $fileinfo) {
        if (str_ends_with($fileinfo->getFilename(), '.info.yml')) {
          $moduleName = $fileinfo->getBasename('.info.yml');
          return (static::$nsAndDir[$objectClassName] = '@' . $moduleName . substr($objectClassDir, strlen($dir)));
        }
      }

      $dir = dirname($dir);
    }
  }

  /**
   * @phpstan-param class-string $objectClassName
   */
  public static function libraryDir(string $objectClassName): string {
    if (isset(static::$libraryDir[$objectClassName])) {
      return static::$libraryDir[$objectClassName];
    }

    /** @var string $fileName */
    $fileName = (new \ReflectionClass($objectClassName))->getFileName();
    $objectClassDir = dirname($fileName);
    if (!\str_starts_with($objectClassDir, \DRUPAL_ROOT)) {
      // Safety! Has not actually happened as far as I know...
      throw new \LogicException(sprintf('Somehow the class is not in the Drupal directory: %s is not in %s', $objectClassDir, \DRUPAL_ROOT));
    }

    return (static::$libraryDir[$objectClassName] = \substr($objectClassDir, strlen(\DRUPAL_ROOT)));
  }

}
