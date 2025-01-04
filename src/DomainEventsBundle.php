<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents;

use Phauthentic\Symfony\DomainEvents\DependencyInjection\DomainEventsExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function dirname;

/**
 * @codeCoverageIgnore
 */
class DomainEventsBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new DomainEventsExtension();
    }
}
