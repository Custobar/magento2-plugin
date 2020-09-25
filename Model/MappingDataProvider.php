<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\MappingDataProvider\DataExtenderInterface;
use Custobar\CustoConnector\Api\Data\MappingDataInterface;
use Magento\Framework\Validation\ValidationException;

class MappingDataProvider implements MappingDataProviderInterface
{
    /**
     * @var DataExtenderInterface
     */
    private $dataExtender;

    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MappingDataInterface[]
     */
    private $mappingDataModels;

    /**
     * @var MappingDataInterface[]
     */
    private $cachedData;

    /**
     * @param DataExtenderInterface $dataExtender
     * @param EntityTypeResolverInterface $typeResolver
     * @param LoggerInterface $logger
     * @param MappingDataInterface[] $mappingDataModels
     */
    public function __construct(
        DataExtenderInterface $dataExtender,
        EntityTypeResolverInterface $typeResolver,
        LoggerInterface $logger,
        array $mappingDataModels = []
    ) {
        $this->dataExtender = $dataExtender;
        $this->typeResolver = $typeResolver;
        $this->logger = $logger;
        $this->mappingDataModels = $mappingDataModels;
    }

    /**
     * @inheritDoc
     */
    public function getAllMappingData()
    {
        if ($this->cachedData === null) {
            $mappingModels = $this->resolveValidModels();
            foreach ($mappingModels as $index => $mappingModel) {
                $mappingModel = $this->dataExtender->extendData($mappingModel);
                $mappingModels[$index] = $mappingModel;
            }

            if (empty($mappingModels)) {
                $this->logger->debug('No mapping data models set');
            }

            $this->cachedData = $mappingModels;
        }

        return $this->cachedData;
    }

    /**
     * @inheritDoc
     */
    public function getMappingDataByEntityType(string $entityType)
    {
        $models = $this->getAllMappingData();

        return $models[$entityType] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getMappingDataByTargetField(string $targetField)
    {
        $models = $this->getAllMappingData();
        foreach ($models as $model) {
            if ($model->getTargetField() == $targetField) {
                return $model;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getMappingDataByObject($entity)
    {
        $entityType = $this->typeResolver->resolveEntityType($entity);

        return $this->getMappingDataByEntityType($entityType);
    }

    /**
     * @return MappingDataInterface[]
     * @throws ValidationException
     */
    private function resolveValidModels()
    {
        $models = [];
        foreach ($this->mappingDataModels as $index => $mappingData) {
            if ($mappingData === null) {
                continue;
            }
            if (!($mappingData instanceof MappingDataInterface)) {
                throw new ValidationException(\__('Mapping data model \'%1\' is not valid', $index));
            }

            $models[$index] = $mappingData;
        }

        return $models;
    }
}
