<?php

declare(strict_types=1);

namespace Drupal\Tests\pinto\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests Pinto rendering.
 *
 * @group pinto
 */
final class PintoTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'pinto_test_routes',
    'pinto_test',
    'pinto',
  ];

  /**
   * Test direct invocation of a theme object.
   *
   * @see \Drupal\pinto_test_routes\Controller\ObjectTestController::__invoke
   * @see \Drupal\pinto_test\Pinto\Generic\ObjectTest
   * @see \Drupal\pinto_test\Pinto\Generic\Objects
   */
  public function testDirectInvocation(): void {
    $this->drupalGet(Url::fromRoute('pinto_test.object_test'));
    $this->assertSession()->responseContains('Test text is foo bar!.');
    $this->assertSession()->responseContains('pinto_test/css/styles.css');
    $this->assertSession()->responseContains('pinto_test/js/app.js');
  }

  /**
   * Test directory based.
   *
   * @see \Drupal\pinto_test_routes\Controller\ObjectDirBased
   * @see \Drupal\pinto_test\Pinto\DirectoryBased\ObjectDirBased
   * @see \Drupal\pinto_test\Pinto\DirectoryBased\DirectoryBased
   */
  public function testDirBased(): void {
    $this->drupalGet(Url::fromRoute('pinto_test.dir_based'));
    $this->assertSession()->responseContains('Dir based template for foo bar!.');
    $this->assertSession()->responseContains('pinto_test/resources/Object_Dir_Based/styles.css');
    $this->assertSession()->responseContains('pinto_test/resources/Object_Dir_Based/app.js');
  }

  /**
   * Test directory based on theme object location.
   *
   * @see \Drupal\pinto_test_routes\Controller\DirBasedWithPhpController::__invoke
   * @see \PintoResources\pinto_test\Object_Dir_Based_With_Php\ThemeObject
   * @see \Drupal\pinto_test\Pinto\DirectoryBasedWithPhp\DirectoryBasedWithPhp
   */
  public function testDirBasedOnThemeObjectLocation(): void {
    $this->drupalGet(Url::fromRoute('pinto_test.dir_based_object_location'));
    $this->assertSession()->responseContains('Dir based template w/ PHP for foo bar!.');
    $this->assertSession()->responseContains('pinto_test/resources/Object_Dir_Based_With_Php/app.js');
    $this->assertSession()->responseContains('pinto_test/resources/Object_Dir_Based_With_Php/styles.css');
  }

  /**
   * Test nested rendering where inner object is predefined in the outer object.
   *
   * @see \Drupal\pinto_test_routes\Controller\NestedPredefinedController::__invoke
   * @see \Drupal\pinto_test\Pinto\Nested\ObjectNested
   * @see \Drupal\pinto_test\Pinto\Nested\ObjectNestedInner
   * @see \Drupal\pinto_test\Pinto\Nested\NestedObjects
   */
  public function testNestedPredefined(): void {
    $this->drupalGet(Url::fromRoute('pinto_test.nested_predefined'));
    $this->assertSession()->responseContains('Start outer inner [<span>[Start Inner [Nested inner text!] End Inner]</span>] End outer inner.');
  }

  /**
   * Test nested rendering where inner object is passed into the outer object.
   *
   * @see \Drupal\pinto_test_routes\Controller\NestedObjectController::__invoke
   * @see \Drupal\pinto_test\Pinto\Nested\ObjectNestedViaVariable
   * @see \Drupal\pinto_test\Pinto\Nested\ObjectNestedInner
   * @see \Drupal\pinto_test\Pinto\Nested\NestedObjects
   */
  public function testNestedObject(): void {
    $this->drupalGet(Url::fromRoute('pinto_test.nested_object'));
    $this->assertSession()->responseContains('Start variable wrapped outer inner [<span>[Start Inner [Nested inner text in a nested variable!] End Inner]</span>] End variable wrapped outer inner.');
  }

}
