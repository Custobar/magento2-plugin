<?php

namespace Custobar\CustoConnector\Model\Validation\SchedulingValidator;

use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Api\SchedulingValidatorInterface;

class CheckHasMappingData implements SchedulingValidatorInterface
{
    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    public function __construct(
        EntityTypeResolverInterface $typeResolver,
        MappingDataProviderInterface $mappingDataProvider
    ) {
        $this->typeResolver = $typeResolver;
        $this->mappingDataProvider = $mappingDataProvider;
    }

    /**
     * @inheritDoc
     */
    public function canScheduleEntity($entity)
    {
        $mappingData = $this->mappingDataProvider->getMappingDataByObject($entity);

        return !empty($mappingData);
    }
}
