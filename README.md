# Key Differences and Advantages of the Class-Based Approach in Drupal

## Code Organization

### Legacy Approach
- All hook implementations are placed in a single file (`legacy_hooks.module`).
- As the module grows, maintenance becomes harder due to scattered functions.

**Example:**
```php
function legacy_hooks_theme() {
  return [
    'custom_template' => [
      'template' => 'custom-template',
      'variables' => ['content' => NULL],
    ],
  ];
}
```

### Class-Based Approach
- Code is structured into separate classes based on responsibility (Controllers, Services, Event Subscribers, Hooks).
- More modular and maintainable.

**Example:**
```php
namespace Drupal\theme_hook_class\Hooks;

use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ThemeHook implements ContainerInjectionInterface {
  protected $themeManager;

  public function __construct(ThemeManagerInterface $themeManager) {
    $this->themeManager = $themeManager;
  }

  #[Hook('theme')]
  public function defineThemeHooks() {
    return [
      'custom_template' => [
        'template' => 'custom-template',
        'variables' => ['content' => NULL],
      ],
    ];
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('theme.manager')
    );
  }
}
```

## Dependency Injection

### Legacy Approach
- Uses static calls (`\Drupal::service()`).
- Difficult to test because services cannot be easily replaced.

**Example:**
```php
$theme_manager = \Drupal::service('theme.manager');
```

### Class-Based Approach
- Uses dependency injection via constructors.
- More testable and decoupled.

**Example:**
```php
public function __construct(ThemeManagerInterface $themeManager) {
  $this->themeManager = $themeManager;
}
```

## Event System vs Hook System

### Legacy Approach
- Relies entirely on Drupal’s hook system.
- Hooks must be declared globally in `.module` files.

### Class-Based Approach
- Uses Symfony’s event system where appropriate.
- Allows event subscribers to react to events without modifying core functionality.

**Example:**
```php
namespace Drupal\theme_hook_class\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\EventSubscriber\MainContentViewSubscriber;

class CustomEventSubscriber implements EventSubscriberInterface {
  public static function getSubscribedEvents() {
    return [
      MainContentViewSubscriber::EVENT_NAME => 'onContentView',
    ];
  }

  public function onContentView($event) {
    // Custom logic.
  }
}
```

## Service-Oriented Architecture

### Legacy Approach
- Uses procedural code with many global functions.
- Difficult to scale and manage dependencies.

### Class-Based Approach
- Implements a service-oriented architecture.
- Components have clearly defined responsibilities, improving modularity and maintainability.

**Example Service Definition (`services.yml`):**
```yaml
services:
  theme_hook_class.theme_service:
    class: Drupal\theme_hook_class\Services\ThemeService
    arguments: ['@theme.manager']
```

## Testability

### Legacy Approach
- Hard to test due to reliance on global functions and static calls.

### Class-Based Approach
- Enables unit and functional testing through dependency injection and class-based organization.

## Reusability

### Legacy Approach
- Code is tightly coupled to hooks, making reuse difficult.

### Class-Based Approach
- Logic is encapsulated in reusable service classes.
- Can be used across different parts of the application, reducing redundancy.

**Example of a reusable service:**
```php
namespace Drupal\theme_hook_class\Services;

use Drupal\Core\Theme\ThemeManagerInterface;

class ThemeService {
  protected $themeManager;

  public function __construct(ThemeManagerInterface $themeManager) {
    $this->themeManager = $themeManager;
  }

  public function getThemeInfo() {
    return $this->themeManager->getActiveTheme();
  }
}
```

## Conclusion
- The class-based approach improves maintainability, testability, and reusability.
- While legacy procedural hooks still work, classes, dependency injection, and event-driven architecture lead to cleaner, more scalable code.

