<?php

declare(strict_types=1);

namespace Drupal\pinto_test\Pinto\Nested;

use Pinto\Attribute\Definition;
use Pinto\List\ObjectListInterface;
use Pinto\List\ObjectListTrait;
use function Safe\realpath;

/**
 * Defines objects to test object nesting.
 */
enum NestedObjects: string implements ObjectListInterface {

  use ObjectListTrait;

  #[Definition(ObjectNested::class)]
  case Nested = 'nested_wrapper';

  #[Definition(ObjectNestedInner::class)]
  case NestedInner = 'nested_inner';

  #[Definition(ObjectNestedViaVariable::class)]
  case ObjectNestedViaVariable = 'nested_wrapper_via_variable';

  public function templateDirectory(): string {
    return '@pinto_test/templates/nested/';
  }

  public function cssDirectory(): string {
    return substr(realpath(__DIR__ . '/../../../css'), \strlen(\DRUPAL_ROOT));
  }

  public function jsDirectory(): string {
    return substr(realpath(__DIR__ . '/../../../js'), \strlen(\DRUPAL_ROOT));
  }

}
