<?php

declare(strict_types=1);

namespace Drupal\class_hooks\Hook;

use Drupal\Core\Hook\Attribute\Hook;

class ThemeHook {

  #[Hook('theme')]
  public function theme(): array {
    return [
      'class_template' => [
        'variables' => ['message' => ''],
      ],
    ];
  }
}
