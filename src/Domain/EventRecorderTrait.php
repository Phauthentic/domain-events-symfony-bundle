<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain;

trait EventRecorderTrait
{
    protected array $domainEvents = [];

    protected function recordThat(object $event): void
    {
        $this->domainEvents[] = $event;
    }
}
