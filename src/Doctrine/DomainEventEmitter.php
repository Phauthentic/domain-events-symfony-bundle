<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Phauthentic\Symfony\DomainEvents\Domain\AggregateExtractorInterface;
use Phauthentic\Symfony\DomainEvents\Domain\AggregateInfo;
use Phauthentic\Symfony\DomainEvents\Messenger\Stamp\AggregateStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * Extract the events from the domain event after the aggregate was persisted.
 *
 * - Extracts events from an aggregate after it was persisted.
 * - Extracts the aggregate ID and type from the aggregate.
 *   - Adds the AggregateStamp to the message bus
 */
#[AsDoctrineListener(
    event: Events::postPersist,
    priority: 1
)]
readonly class DomainEventEmitter
{
    public function __construct(
        private MessageBusInterface $domainEventBus,
        private LoggerInterface $logger,
        private AggregateExtractorInterface $aggregateExtractor,
    ) {
    }

    protected function getAggregateInfoFromEntity(object $entity): ?AggregateInfo
    {
        $aggregateInfo = $this->aggregateExtractor->extractAggregate($entity);
        if ($aggregateInfo) {
            return $aggregateInfo;
        }

        $this->logger->debug(sprintf(
            'No aggregate info found for %s',
            get_class($entity)
        ));

        return null;
    }

    public function postPersist(PostPersistEventArgs $postPersistEventArgs): void
    {
        $entity = $postPersistEventArgs->getObject();

        $aggregateInfo = $this->getAggregateInfoFromEntity($entity);
        if (!$aggregateInfo) {
            return;
        }

        $this->emitDomainEvents($entity, $aggregateInfo);
    }

    protected function emitDomainEvents(object $entity, AggregateInfo $aggregateInfo): void
    {
        foreach ($aggregateInfo->getDomainEvents() as $event) {
            $this->emitEvent($event, $entity, $aggregateInfo);
        }
    }

    protected function buildAggregateStamp(
        object $entity,
        AggregateInfo $aggregateInfo
    ): AggregateStamp {
        return new AggregateStamp(
            aggregateId: $aggregateInfo->getAggregateId(),
            aggregateType: get_class($entity)
        );
    }

    /**
     * @param mixed $event
     * @param object $entity
     * @param AggregateInfo $aggregateInfo
     * @return void
     * @throws DomainEventEmitterException
     */
    protected function emitEvent(mixed $event, object $entity, AggregateInfo $aggregateInfo): void
    {
        try {
            $this->domainEventBus->dispatch($event, [
                $this->buildAggregateStamp($entity, $aggregateInfo),
            ]);
        } catch (Throwable $throwable) {
            $this->logger->alert(sprintf(
                'Failed to dispatched event %s',
                get_class($event)
            ));

            throw DomainEventEmitterException::failedToDispatchEvent($event, $throwable);
        }
    }
}
