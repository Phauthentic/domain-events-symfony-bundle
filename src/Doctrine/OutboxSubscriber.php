<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Phauthentic\Symfony\DomainEvents\Domain\AggregateExtractorInterface;
use Phauthentic\Symfony\DomainEvents\Domain\Repository\OutboxRepositoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 */
#[AsDoctrineListener(
    event: Events::prePersist,
    priority: 1
)]
readonly class OutboxSubscriber
{
    public function __construct(
        private AggregateExtractorInterface $aggregateExtractor,
        private OutboxRepositoryInterface $outboxRepository,
        private SerializerInterface $serializer
    ) {
    }

    public function prePersist(PrePersistEventArgs $postPersistEventArgs): void
    {
        $entity = $postPersistEventArgs->getObject();

        $aggregateInfo = $this->aggregateExtractor->extractAggregate($entity);
        if (!$aggregateInfo) {
            return;
        }

        foreach ($aggregateInfo->getDomainEvents() as $event) {
            $this->persistTheEventInTheBox($event);
        }

        $this->aggregateExtractor->purgeDomainEvents($entity);
    }

    public function persistTheEventInTheBox(object $event): void
    {
        $event = $this->serializer->serialize($event, 'json');

        $this->outboxRepository->persist($event);
    }
}
