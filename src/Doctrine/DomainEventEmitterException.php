<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Doctrine;

use Exception;
use Phauthentic\Symfony\DomainEvents\Domain\Attribute\AggregateRoot;
use ReflectionClass;
use Throwable;

class DomainEventEmitterException extends Exception
{
    public static function missingAggregateId(object $object): self
    {
        return new self(sprintf(
            'Aggregate ID not found in %s',
            get_class($object)
        ));
    }

    /**
     * @param AggregateRoot $aggregateRootAttribute
     * @param ReflectionClass<object> $reflectionClass
     * @return self
     */
    public static function missingDomainEvents(AggregateRoot $aggregateRootAttribute, ReflectionClass $reflectionClass): self
    {
        return new self(sprintf(
            'Property %s not found in class %s',
            $aggregateRootAttribute->domainEvents,
            $reflectionClass->getName()
        ));
    }

    public static function failedToDispatchEvent(object $event, Throwable $throwable): self
    {
        throw new self(sprintf(
            'Failed to dispatch event %s: %s',
            get_class($event),
            $throwable->getMessage()
        ), previous: $throwable);
    }
}
