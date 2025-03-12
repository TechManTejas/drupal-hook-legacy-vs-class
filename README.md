# Drupal 11: Legacy Hooks vs. Class-Based Hooks

This repository demonstrates the use of hooks in Drupal 11 using both the legacy procedural approach and the modern class-based approach.

## Key Differences and Advantages of Class-Based Approach

### 1. Code Organization

- **Legacy Approach**: All hook implementations are in a single file (`legacy_hooks.module`), making it harder to maintain as the module grows.
- **Class-Based Approach**: Code is organized into specific classes by responsibility (Controllers, Services, Event Subscribers, etc.), making it more maintainable.

#### Example:
**Legacy Approach:**
```php
function legacy_hooks_theme() {
  return [
    'legacy_template' => [
      'variables' => ['message' => NULL],
    ],
  ];
}
```

**Class-Based Approach:**
```php
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
```

---

### 2. Dependency Injection

- **Legacy Approach**: Uses `\Drupal::service()` static calls, making testing difficult.
- **Class-Based Approach**: Uses proper dependency injection via constructors, making services testable and decoupled.

#### Example:
**Legacy Approach:**
```php
$logger = \Drupal::service('logger.channel.default');
$logger->info('This is a log message.');
```

**Class-Based Approach:**
```php
use Psr\Log\LoggerInterface;

class MyService {
  private LoggerInterface $logger;

  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public function logMessage() {
    $this->logger->info('This is a log message.');
  }
}
```

---

### 3. Event System vs. Hook System

- **Legacy Approach**: Relies entirely on Drupal's hook system.
- **Class-Based Approach**: Uses Symfony's event system where appropriate, while still implementing hooks where needed.

#### Example (Event Subscriber):
```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CustomSubscriber implements EventSubscriberInterface {
  public static function getSubscribedEvents() {
    return [ResponseEvent::class => 'onResponse'];
  }

  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();
    $response->headers->set('X-Custom-Header', 'Drupal 11 Rocks!');
  }
}
```

---

### 4. Service-Oriented Architecture

- **Legacy Approach**: Procedural code with global functions.
- **Class-Based Approach**: Uses services with clearly defined responsibilities.

#### Example (Defining a Service):
```yaml
services:
  class_hooks.custom_service:
    class: Drupal\class_hooks\Service\CustomService
    arguments: ['@logger.factory']
```

**Corresponding PHP Class:**
```php
namespace Drupal\class_hooks\Service;

use Psr\Log\LoggerInterface;

class CustomService {
  private LoggerInterface $logger;

  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public function execute() {
    $this->logger->info('Service executed successfully.');
  }
}
```

---

### 5. Testability

- **Legacy Approach**: Hard to test due to global functions and static service calls.
- **Class-Based Approach**: Designed for testability through dependency injection and class-based organization.

#### Example (Unit Test for Class-Based Approach):
```php
use Drupal\Tests\UnitTestCase;
use Drupal\class_hooks\Service\CustomService;
use Psr\Log\LoggerInterface;

class CustomServiceTest extends UnitTestCase {
  public function testExecute() {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('info');

    $service = new CustomService($logger);
    $service->execute();
  }
}
```

---

### 6. Reusability

- **Legacy Approach**: Code is tightly coupled to hooks, making reuse difficult.
- **Class-Based Approach**: Services can be reused throughout your application.

#### Example:
Instead of calling `\Drupal::service()` everywhere, services are injected and reusable:

```php
use Drupal\class_hooks\Service\CustomService;

class SomeController {
  private CustomService $customService;

  public function __construct(CustomService $customService) {
    $this->customService = $customService;
  }

  public function doSomething() {
    $this->customService->execute();
  }
}
```

---

## Conclusion

The class-based approach in Drupal 11 improves maintainability, testability, and modularity while allowing seamless integration with Symfony's event-driven architecture. By organizing hooks into dedicated classes and leveraging services, the overall code quality and maintainability significantly improve.

