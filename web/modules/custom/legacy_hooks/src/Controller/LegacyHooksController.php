<?php

namespace Drupal\legacy_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Legacy Hooks routes.
 */
class LegacyHooksController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    return [
      '#theme' => 'legacy_template',
      '#message' => 'This is rendered using the legacy way of implementing theme hooks!',
    ];
  }
}
