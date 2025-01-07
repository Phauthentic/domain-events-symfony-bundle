<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Tests\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phauthentic\Symfony\DomainEvents\Domain\EventRecorderTrait;

/**
 *
 */
class EventRecorderTraitTest extends TestCase
{
    #[Test]
    public function testRecordThat(): void
    {
        $dummy = new class {
            use EventRecorderTrait;

            public function triggerEvent(): void
            {
                $this->recordThat(new \stdClass());
            }

            public function getDomainEvents(): array
            {
                return $this->domainEvents;
            }
        };

        $event = new \stdClass();
        $dummy->triggerEvent();

        $this->assertCount(1, $dummy->getDomainEvents());
    }
}
