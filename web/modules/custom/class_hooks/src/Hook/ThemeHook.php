<?php

declare(strict_types=1);

namespace Drupal\class_hooks\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ThemeHook {
  
  protected RouteMatchInterface $routeMatch;

  public function __construct(RouteMatchInterface $routeMatch) {
    $this->routeMatch = $routeMatch;
  }

  #[Hook('theme')]
  public function theme(): array {
    return [
      'class_template' => [
        'variables' => ['message' => ''],
      ],
    ];
  }

  #[Hook('page_attachments')]
  public function addPageAttachments(array &$attachments): void {
    // Get the current route name.
    $route_name = $this->routeMatch->getRouteName();

    // Attach the custom library only for the "/class" route.
    if ($route_name === 'class_hooks.page') {
      $attachments['#attached']['library'][] = 'class_hooks/custom_styles';
    }
  }

  public static function create(ContainerInterface $container): self {
    return new self($container->get('current_route_match'));
  }
}
