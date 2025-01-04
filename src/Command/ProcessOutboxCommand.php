<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\Command;

use Phauthentic\Symfony\DomainEvents\Domain\Repository\OutboxRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'domain-events:process-outbox',
    description: 'Processes all entries from the outbox and sends them via the message bus.'
)]
class ProcessOutboxCommand extends Command
{
    public function __construct(
        private readonly OutboxRepositoryInterface $outboxRepository,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Processes all entries from the outbox and sends them via the message bus.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        while (true) {
            $outboxMessages = $this->outboxRepository->findAll();

            if (empty($outboxMessages)) {
                $io->info('No messages to process. Waiting for 3 seconds...');
                sleep(3);
                continue;
            }

            foreach ($outboxMessages as $outboxMessage) {
                $this->messageBus->dispatch($outboxMessage->getMessage());
                $this->outboxRepository->remove($outboxMessage);
            }

            $this->outboxRepository->flush();
            $io->success('Processed all outbox messages.');
        }
    }
}
