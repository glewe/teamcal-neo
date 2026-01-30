<?php
declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Template Engine
 *
 * Wrapper for the Twig templating engine.
 *
 * @package TeamCal Neo
 */
class TemplateEngine
{
  private Environment $twig;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param string $viewPath Path to the views directory
   * @param array  $options  Twig environment options (cache, debug, etc.)
   */
  public function __construct(string $viewPath, array $options = []) {
    $loader     = new FilesystemLoader($viewPath);
    $this->twig = new Environment($loader, $options);

    // Register legacy view helpers as Twig functions
    $helpers = [
      'createAlertBox',
      'createFormGroup',
      'createModalTop',
      'createModalBottom',
      'createPageTabs',
      'createToast',
      'createFaIconListbox',
      'createPatternTable',
      'createIconTooltip',
      'iconTooltip',
      'isAllowed',
      'nextTabindex',
      'resetTabindex'
    ];

    foreach ($helpers as $helper) {
      $this->twig->addFunction(new \Twig\TwigFunction($helper, $helper, ['is_safe' => ['html']]));
    }

    // Register custom filters
    $filters = [
      'url_decode' => 'urldecode',
    ];

    foreach ($filters as $name => $function) {
      $this->twig->addFilter(new \Twig\TwigFilter($name, $function));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Render a template.
   *
   * @param string $template Template name (e.g., 'login.twig')
   * @param array  $data     Data to pass to the template
   *
   * @return string Rendered HTML
   */
  public function render(string $template, array $data = []): string {
    // Add .twig extension if missing
    if (!str_ends_with($template, '.twig')) {
      $template .= '.twig';
    }

    return $this->twig->render($template, $data);
  }

  //---------------------------------------------------------------------------
  /**
   * Get the internal Twig Environment instance.
   *
   * @return Environment
   */
  public function getTwig(): Environment {
    return $this->twig;
  }
}
