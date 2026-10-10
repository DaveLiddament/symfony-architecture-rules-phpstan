<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Roles;

use DaveLiddament\Architecture\Attribute\CliCommand;
use DaveLiddament\Architecture\Attribute\ConfigProvider;
use DaveLiddament\Architecture\Attribute\Controller;
use DaveLiddament\Architecture\Attribute\Dto;
use DaveLiddament\Architecture\Attribute\Entity;
use DaveLiddament\Architecture\Attribute\QueueGateway;
use DaveLiddament\Architecture\Attribute\QueueProcessor;
use DaveLiddament\Architecture\Attribute\Repository;
use DaveLiddament\Architecture\Attribute\Serializer;
use DaveLiddament\Architecture\Attribute\Service;
use DaveLiddament\Architecture\Attribute\ValueObject;
use DaveLiddament\Architecture\Attribute\ViewModel;

/**
 * The roles a class can play. Each value is the role's key in the
 * architecture.roles and architecture.roleAliases config.
 *
 * Most roles have an attribute. A form type has none: it only exists in
 * Symfony, so it is recognised through the Symfony preset's alias.
 */
enum Role: string
{
    case CliCommand = 'cliCommand';
    case ConfigProvider = 'configProvider';
    case Controller = 'controller';
    case Dto = 'dto';
    case Entity = 'entity';
    case FormType = 'formType';
    case QueueGateway = 'queueGateway';
    case QueueProcessor = 'queueProcessor';
    case Repository = 'repository';
    case Serializer = 'serializer';
    case Service = 'service';
    case ValueObject = 'valueObject';
    case ViewModel = 'viewModel';

    /**
     * @return class-string|null
     */
    public function attributeClass(): ?string
    {
        return match ($this) {
            self::CliCommand => CliCommand::class,
            self::ConfigProvider => ConfigProvider::class,
            self::Controller => Controller::class,
            self::Dto => Dto::class,
            self::Entity => Entity::class,
            self::FormType => null,
            self::QueueGateway => QueueGateway::class,
            self::QueueProcessor => QueueProcessor::class,
            self::Repository => Repository::class,
            self::Serializer => Serializer::class,
            self::Service => Service::class,
            self::ValueObject => ValueObject::class,
            self::ViewModel => ViewModel::class,
        };
    }

    /**
     * Whether a class with this role may be #[Exported] to other domains.
     * Entry points are invoked by the framework, so nothing should depend
     * on them; view models, serializers and config providers are details of
     * one domain.
     */
    public function isExportable(): bool
    {
        return match ($this) {
            self::CliCommand, self::ConfigProvider, self::Controller, self::FormType,
            self::QueueProcessor, self::Serializer, self::ViewModel => false,
            self::Dto, self::Entity, self::QueueGateway, self::Repository, self::Service, self::ValueObject => true,
        };
    }
}
