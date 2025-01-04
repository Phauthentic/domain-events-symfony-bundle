<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain;

interface EventSourcedAggregateInterface extends AggregateRootInterface
{
    public function getEvents(): string;
}
