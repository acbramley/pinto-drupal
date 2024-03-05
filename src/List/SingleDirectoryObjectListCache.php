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
  public static array $nsAndDir = [];

  /**
   * @phpstan-param class-string $objectClassName
   */
  public static function nsAndDir(string $objectClassName): string {
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

}
