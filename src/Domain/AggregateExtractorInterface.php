<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Domain;

/**
 *
 */
interface AggregateExtractorInterface
{
    public function extractAggregate(object $aggregate): ?AggregateInfo;
}
