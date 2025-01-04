<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain\Attribute;

use Attribute;

/**
 * Describes an aggregate roots properties.
 *
 * The following properties can be configured:
 *
 * - aggregateId: The property name of the aggregate id
 * - domainEvents: The property name of the domain events
 * - aggregateVersion: The property name of the aggregate version
 *
 * They are used to extract them via reflection from the aggregate root.
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class AggregateRoot
{
    public function __construct(
        public string $aggregateId = 'id',
        public string $domainEvents = 'domainEvents',
        public ?string $aggregateVersion = null
    ) {
    }
}
