<?php

namespace Custobar\CustoConnector\Model\Validation\SchedulingValidator;

use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Api\SchedulingValidatorInterface;

class CheckHasMappingData implements SchedulingValidatorInterface
{
    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @param MappingDataProviderInterface $mappingDataProvider
     */
    public function __construct(
        MappingDataProviderInterface $mappingDataProvider
    ) {
        $this->mappingDataProvider = $mappingDataProvider;
    }

    /**
     * @inheritDoc
     */
    public function canScheduleEntity($entity)
    {
        $mappingData = $this->mappingDataProvider->getMappingDataByObject($entity);

        return (bool)$mappingData;
    }

    /**
     * @inheritDoc
     */
    public function canScheduleEntityTypeAndIds(array $entityIds, string $entityType)
    {
        $mappingData = $this->mappingDataProvider->getMappingDataByEntityType($entityType);
        $valid = (bool)$mappingData;

        return \array_fill_keys($entityIds, $valid);
    }
}
