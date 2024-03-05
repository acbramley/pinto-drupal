<?php

declare(strict_types=1);

namespace Drupal\pinto;

use Drupal\Core\Render\Element;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Pinto\Exception\PintoMissingObjectMapping;
use Pinto\PintoMapping;

/**
 * Renders objects in render arrays.
 *
 * Glue for calling builder methods automatically if a Pinto Theme Object is
 * added to a render array.
 *
 * E.g:
 * <code>
 *   $build = [
 *     'foo' => ['#markup' => 'bar'],
 *     'baz' => \Drupal\my_module\Pinto\Objects\MyObject::create(...),
 *   ];
 * </code>
 */
final readonly class Renderer implements RendererInterface {

  /**
   * Constructor.
   */
  public function __construct(
    private RendererInterface $inner,
    private PintoMapping $mapping,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function renderRoot(&$elements) {
    return $this->inner->renderRoot($elements);
  }

  /**
   * {@inheritdoc}
   */
  public function renderPlain(&$elements) {
    return $this->inner->renderPlain($elements);
  }

  /**
   * {@inheritdoc}
   */
  public function renderPlaceholder($placeholder, array $elements) {
    return $this->inner->renderPlaceholder($placeholder, $elements);
  }

  /**
   * {@inheritdoc}
   */
  public function render(&$elements, $is_root_call = FALSE) {
    // Does this need to be used by other methods of this class?
    $this->processBuildInvokers($elements);
    return $this->inner->render($elements, $is_root_call);
  }

  /**
   * {@inheritdoc}
   */
  public function hasRenderContext() {
    return $this->inner->hasRenderContext();
  }

  /**
   * {@inheritdoc}
   */
  public function executeInRenderContext(RenderContext $context, callable $callable) {
    return $this->inner->executeInRenderContext($context, $callable);
  }

  /**
   * {@inheritdoc}
   */
  public function mergeBubbleableMetadata(array $a, array $b) {
    return $this->inner->mergeBubbleableMetadata($a, $b);
  }

  /**
   * {@inheritdoc}
   */
  public function addCacheableDependency(array &$elements, $dependency): void {
    $this->inner->addCacheableDependency($elements, $dependency);
  }

  /**
   * Calls the build method of any object instance in a render array.
   */
  private function processBuildInvokers(array &$elements): void {
    $mapping = $this->mapping;
    ($loop = static function (&$elements) use (&$loop, $mapping): void {
      foreach ($elements as $k => &$element) {
        if (is_object($element)) {
          try {
            $methodName = $mapping->getBuildInvoker($element::class);
            // @phpstan-ignore-next-line
            $elements[$k] = call_user_func([$element, $methodName]);
          }
          catch (PintoMissingObjectMapping) {
          }
        }
      }

      foreach (Element::children($elements) as $k) {
        $loop($elements[$k]);
      }
    })($elements);
  }

}
