<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 *
 */
readonly final class AggregateStamp implements StampInterface
{
    public function __construct(
        private string $aggregateId,
        private string $aggregateType
    ) {
    }

    public function getAggregateType(): string
    {
        return $this->aggregateType;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }
}
