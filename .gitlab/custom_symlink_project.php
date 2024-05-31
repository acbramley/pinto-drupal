#!/usr/bin/env php
<?php

# Pinto custom:
# Originally at,
# https://git.drupalcode.org/project/gitlab_templates/-/blob/main/scripts/symlink_project.php

/**
 * @file
 * Symlink the files from this project into Drupal's modules/custom directory.
 *
 * Param 1 = Project name
 * Param 2 = Path from webRoot to modules directory. This will vary depending on
 *           whether running a Drupal 7 or Drupal 10 pipeline. If none supplied
 *           then default to the Drupal 10 path.
 *
 * This script is also used for Drupal 7, which can go as low as PHP5.6, so do
 * not use new PHP features and make sure that any change here is tested
 * against PHP5.6. Therefore specific phpcs rules are disabled for this file.
 */

// phpcs:disable SlevomatCodingStandard.ControlStructures.RequireNullCoalesceOperator
// phpcs:disable Drupal.Arrays.Array.CommaLastItem

use Symfony\Component\Filesystem\Filesystem;

require __DIR__ . '/vendor/autoload.php';
$project_name = isset($argv[1]) ? $argv[1] : getenv('CI_PROJECT_NAME');
$pathToModules = isset($argv[2]) ? $argv[2] : 'modules/custom';

if (empty($project_name)) {
  throw new RuntimeException('Unable to determine project name.');
}
$fs = new Filesystem();

// Directory where the root project is being created.
$projectRoot = getcwd();

// Directory where the contrib module symlinks are to be created.
$webRoot = getenv('_WEB_ROOT') ?: 'web';
$moduleRoot = $projectRoot . '/' . $webRoot . '/' . $pathToModules . '/' . $project_name;

// Prepare directory for current module.
if ($fs->exists($moduleRoot)) {
  $fs->remove($moduleRoot);
}
$fs->mkdir($moduleRoot);
echo "CREATING COPIES OF FILES FROM $projectRoot to $moduleRoot\n";
foreach (scandir($projectRoot) as $item) {
  if (!in_array($item, [
    '.',
    '..',
    '.git',
    '.idea',
    'drush',
    'vendor',
    $webRoot,
    'symlink_project.php',
    'custom_symlink_project.php',
    'expand_composer_json.php',
    'composer.json.backup',
    // PINTO:
    'build.env',
  ])) {
    $source = sprintf('%s/%s', $projectRoot, $item);
    $target = sprintf('%s/%s', $moduleRoot, $item);
    \is_file($source)
      ? $fs->copy($source, $target)
      : $fs->mirror($source, $target);
  }
}
