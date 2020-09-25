<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\EntityDataConverterInterface;
use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;

class EntityDataConverter implements EntityDataConverterInterface
{
    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var EntityDataConverterInterface[]
     */
    private $converters;

    /**
     * @param EntityTypeResolverInterface $typeResolver
     * @param MappingDataProviderInterface $mappingDataProvider
     * @param EntityDataConverterInterface[] $converters
     */
    public function __construct(
        EntityTypeResolverInterface $typeResolver,
        MappingDataProviderInterface $mappingDataProvider,
        array $converters = []
    ) {
        $this->typeResolver = $typeResolver;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->converters = $converters;
    }

    /**
     * @inheritDoc
     */
    public function convertToMappedEntity($entity)
    {
        $entityType = $this->typeResolver->resolveEntityType($entity);
        $mappingData = $this->mappingDataProvider->getMappingDataByEntityType($entityType);
        if ($mappingData) {
            return $entity;
        }

        $converter = $this->converters[$entityType] ?? null;
        if (!($converter instanceof EntityDataConverterInterface)) {
            return null;
        }

        return $converter->convertToMappedEntity($entity);
    }
}
