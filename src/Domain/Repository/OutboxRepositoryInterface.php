<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain\Repository;

/**
 *
 */
interface OutboxRepositoryInterface
{
    public function persist(Message $message): void;

    public function getUnsentMessages(): array;
}
