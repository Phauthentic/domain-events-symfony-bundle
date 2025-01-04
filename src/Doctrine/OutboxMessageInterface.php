<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Doctrine;

interface OutboxMessageInterface
{
    public function getEvent(): string;
}
