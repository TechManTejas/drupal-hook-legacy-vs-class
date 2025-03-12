<?php

declare(strict_types=1);

namespace Drupal\class_hooks\Hook;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Class to demonstrate dependency injection in hooks.
 */
class HelpHook {

  protected ModuleHandlerInterface $moduleHandler;

  /**
   * Constructor for dependency injection.
   */
  public function __construct(ModuleHandlerInterface $moduleHandler) {
    $this->moduleHandler = $moduleHandler;
  }

  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match): string {
    if ($route_name === 'help.page.class_hooks') {
      return '<p>This module demonstrates the class-based way of implementing hooks with dependency injection.</p>';
    }
    return '';
  }
}
