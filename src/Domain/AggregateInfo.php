<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain;

/**
 *
 */
class AggregateInfo
{
    /**
     * @param string $aggregateId
     * @param array<int, object> $domainEvents
     * @param int|null $aggregateVersion
     */
    public function __construct(
        private string $aggregateId,
        private array $domainEvents,
        private ?int $aggregateVersion
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @codeCoverageIgnore
     * @return array<int, object>
     */
    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAggregateVersion(): ?int
    {
        return $this->aggregateVersion;
    }
}
