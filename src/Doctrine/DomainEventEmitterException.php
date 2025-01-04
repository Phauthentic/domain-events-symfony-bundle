<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Doctrine;

use Exception;
use Throwable;

class DomainEventEmitterException extends Exception
{
    public static function missingAggregateId(object $object): self
    {
        return new self(sprintf(
            'Aggregate ID not found in %s',
            get_class($object)
        ));
    }

    public static function failedToDispatchEvent(object $event, Throwable $throwable): self
    {
        throw new self(sprintf(
            'Failed to dispatch event %s: %s',
            get_class($event),
            $throwable->getMessage()
        ), previous: $throwable);
    }
}
