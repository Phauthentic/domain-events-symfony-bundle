<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Tests\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Phauthentic\Symfony\DomainEvents\Doctrine\DomainEventEmitter;
use Phauthentic\Symfony\DomainEvents\Domain\ReflectionAggregateExtractor;
use Phauthentic\Symfony\DomainEvents\Tests\TestAggregate;
use Phauthentic\Symfony\DomainEvents\Tests\TestEvent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 *
 */
class DomainEventEmitterTest extends TestCase
{
    private $domainEventBus;
    private $logger;
    private $aggregateExtractor;
    private $domainEventEmitter;

    protected function setUp(): void
    {
        $this->domainEventBus = $this->createMock(MessageBusInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->aggregateExtractor = new ReflectionAggregateExtractor();

        $this->domainEventEmitter = new DomainEventEmitter(
            $this->domainEventBus,
            $this->logger,
            $this->aggregateExtractor
        );
    }

    #[Test]
    public function testPostPersistWithOneEvent(): void
    {
        $entity = new TestAggregate();
        $entity->addEvent(new TestEvent());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $postPersistEventArgs = new PostPersistEventArgs($entity, $entityManager);

        $this->domainEventBus
            ->expects($this->once())
            ->method('dispatch');

        $this->domainEventEmitter->postPersist($postPersistEventArgs);
    }

    #[Test]
    public function testPostPersistWithNoEvents(): void
    {
        $entity = new TestAggregate();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $postPersistEventArgs = new PostPersistEventArgs($entity, $entityManager);

        $this->domainEventBus
            ->expects($this->never())
            ->method('dispatch');

        $this->domainEventEmitter->postPersist($postPersistEventArgs);
    }

    #[Test]
    public function testPostPersistWithNoneAggregate(): void
    {
        $entity = new stdClass();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $postPersistEventArgs = new PostPersistEventArgs($entity, $entityManager);

        $this->domainEventBus
            ->expects($this->never())
            ->method('dispatch');

        $this->domainEventEmitter->postPersist($postPersistEventArgs);
    }
}
