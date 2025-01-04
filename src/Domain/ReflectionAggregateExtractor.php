<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain;

use Phauthentic\Symfony\DomainEvents\Doctrine\DomainEventEmitterException;
use Phauthentic\Symfony\DomainEvents\Domain\Attribute\AggregateRoot;
use ReflectionAttribute;
use ReflectionClass;
use RuntimeException;

/**
 *
 */
class ReflectionAggregateExtractor implements AggregateExtractorInterface
{
    public function extractAggregate(object $aggregate): ?AggregateInfo
    {
        $reflectionClass = new ReflectionClass($aggregate);

        $aggregateRootAttribute = $this->getAggregateRootAttribute($reflectionClass);

        if ($aggregateRootAttribute === null) {
            return null;
        }

        $aggregateIdPropertyName = $aggregateRootAttribute->aggregateId;
        $eventPropertyName = $aggregateRootAttribute->domainEvents;
        $aggregateVersionPropertyName = $aggregateRootAttribute->aggregateVersion;

        $aggregateVersion = null;
        if ($aggregateVersionPropertyName) {
            $aggregateVersion = $this->getProperty($aggregateVersionPropertyName, $reflectionClass, $aggregate);
        }

        return new AggregateInfo(
            (string)$this->getProperty($aggregateIdPropertyName, $reflectionClass, $aggregate),
            $this->getProperty($eventPropertyName, $reflectionClass, $aggregate),
            $aggregateVersion
        );
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @return AggregateRoot|null
     */
    protected function getAggregateRootAttribute(ReflectionClass $reflectionClass): ?AggregateRoot
    {
        $attributes = $reflectionClass->getAttributes(AggregateRoot::class);

        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }

    protected function getProperty(
        string $propertyName,
        ReflectionClass $reflectionClass,
        object $aggregate
    ): mixed {
        if (!$reflectionClass->hasProperty($propertyName)) {
            throw new RuntimeException(sprintf(
                'Property %s not found in class %s',
                $propertyName,
                $reflectionClass->getName()
            ));
        }

        return $reflectionClass
            ->getProperty($propertyName)
            ->getValue($aggregate);
    }

    public function purgeDomainEvents(object $aggregate): void
    {
        $reflectionClass = new ReflectionClass($aggregate);
        $aggregateRootAttribute = $this->getAggregateRootAttribute($reflectionClass);

        if (!$aggregateRootAttribute) {
            return;
        }

        if ($reflectionClass->hasProperty($aggregateRootAttribute->domainEvents) === false) {
            throw new DomainEventEmitterException(sprintf(
                'Property %s not found in class %s',
                $aggregateRootAttribute->domainEvents,
                $reflectionClass->getName()
            ));
        }

        $reflectionClass
            ->getProperty($aggregateRootAttribute->domainEvents)
            ->setValue($aggregate, []);
    }
}
