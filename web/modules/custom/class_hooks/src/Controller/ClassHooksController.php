<?php

namespace Drupal\class_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Class Hooks routes.
 */
class ClassHooksController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    return [
      '#theme' => 'class_template',
      '#message' => 'This is rendered using the class-based way of implementing theme hooks!',
    ];
  }
}
