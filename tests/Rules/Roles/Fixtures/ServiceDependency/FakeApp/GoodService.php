<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service]
final readonly class GoodService
{
    /**
     * @param iterable<SomeInterface> $handlers
     * @param list<OtherService> $workers
     */
    public function __construct(
        private OtherService $service,
        private ?OtherService $maybeService,
        private SomeRepository $repository,
        private SomeGateway $gateway,
        private SomeSerializer $serializer,
        private TheConfig $config,
        private SomeInterface $contract,
        private \ArrayObject $vendorish,
        private iterable $handlers,
        private array $workers,
    ) {
    }
}
