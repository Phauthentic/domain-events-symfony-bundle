<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Tests;

use Phauthentic\Symfony\DomainEvents\Domain\Attribute\AggregateRoot;

/**
 *
 */
#[AggregateRoot]
class TestAggregate
{
    protected string $id = '1';

    protected array $domainEvents = [];

    public function addEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }
}
