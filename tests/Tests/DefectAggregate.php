<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Tests;

use Phauthentic\Symfony\DomainEvents\Domain\Attribute\AggregateRoot;

/**
 * Used for testing a defect aggregate that doesn't have the required properties.
 */
#[AggregateRoot(
    aggregateId: 'id',
    domainEvents: 'domainEvents'
)]
class DefectAggregateRoot
{
}
