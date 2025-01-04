# Symfony Domain Events Bundle

This bundle deals with the dispatching of domain events and the implementation of the outbox pattern, which is optional.

The outbox pattern is a way to ensure reliable message publishing with a dedicated “Outbox” table within your transactional boundary. It is suited to scenarios needing atomic writes to business data and events. It is simple to implement using existing relational databases.

This library follows the KISS principle and tries to keep things as simple and easy to understand as possible. Being framework agnostic was never a design goal, nor being super generic.

## Features

* Domain events dispatching
* (Optional) Outbox pattern implementation

## Installation

```bash
composer require phauthentic/domain-events-symfony-bundle
```

Wire the services in your `config/services.yaml`:

```yaml
services:
    Phauthentic\Symfony\DomainEvents\Domain\ReflectionAggregateExtractor:
    Phauthentic\Symfony\DomainEvents\Domain\AggregateExtractorInterface: '@Phauthentic\Symfony\DomainEvents\Domain\ReflectionAggregateExtractor'
    Phauthentic\Symfony\DomainEvents\Doctrine\DomainEventEmitter:
      tags:
        - { name: 'doctrine.event_listener', event: 'postPersist', priority: 1 }
```

## Usage

Add the `#[AggregateRoot]` attribute to your aggregate root classes that should emit domain events. By default the AggregateRoot attribute will look for an `id` and `domainEvents` property in your aggregate root classes. You can customize this by passing the property names as arguments to the attribute.

Optional: Use the EventRecorderTrait in your aggregate root classes to record domain events if you want to stick to the defaults. The trait implements the `domainEvents` property and adds a method `recordThat(object $event)`.

### Minimum default example

```php
#[AggregateRoot]
class Cart {

    use EventRecorderTrait;

    string $id;
    
    public function itemAdded(Item $item) {
        $this->items[] = $item;
        $this->recordThat(new ItemAdded($item));
    }
}
```

## Domain Events, the Outbox Pattern and Event Sourcing

The bundle is intentionally not implementing event sourcing, because it adds another level of often not needed complexity. If you struggle to understand this design decision you are probably not a candidate for event sourcing in any case.

### The Outbox Pattern

- Ensures reliable message publishing with a dedicated “Outbox” table within your transactional boundary.
- Suited to scenarios needing atomic writes to business data and events.
- Simple to implement using existing relational databases.

### Event Store / Event Sourcing

- Stores **every** event as the system’s ultimate source of truth.
- Suited to event-sourced architectures, allowing system state to be rebuilt from event history.
- Provides full auditability and traceability but is **more complex**.

#### Event Sourcing Alternatives

- [Phauthentic Event Sourcing](https://github.com/Phauthentic/event-sourcing)
- [Ecotone Framework](https://docs.ecotone.tech/)
- [Event Sauce](https://eventsauce.io/)
- [Broadway](https://github.com/broadway/broadway)

## License

This bundle is under the MIT license.

Copyright Florian Krämer
