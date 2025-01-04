<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Tests\Domain;

use Phauthentic\Symfony\DomainEvents\Domain\ReflectionAggregateExtractor;
use Phauthentic\Symfony\DomainEvents\Tests\TestAggregate;
use Phauthentic\Symfony\DomainEvents\Tests\TestEvent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 *
 */
class ReflectionAggregateExtractorTest extends TestCase
{
    #[Test]
    public function testSuccessfulExtraction(): void
    {
        $extractor = new ReflectionAggregateExtractor();

        $result = $extractor->extractAggregate(new TestAggregate());
        $this->assertNotNull($result);
        $this->assertEquals('1', $result->getAggregateId());
        $this->assertEquals([], $result->getDomainEvents());
        $this->assertEquals(null, $result->getAggregateVersion());
    }

    #[Test]
    public function testExtractAggregateWithoutAttribute(): void
    {
        $extractor = new ReflectionAggregateExtractor();

        $result = $extractor->extractAggregate(new stdClass());
        $this->assertNull($result);
    }

    #[Test]
    public function testPurging(): void
    {
        $aggregate = new TestAggregate();
        $aggregate->addEvent(new TestEvent());
        $aggregate->addEvent(new TestEvent());

        $this->assertCount(2, $aggregate->getDomainEvents());

        $extractor = new ReflectionAggregateExtractor();

        $extractor->purgeDomainEvents($aggregate);

        $this->assertCount(0, $aggregate->getDomainEvents());
    }
}
