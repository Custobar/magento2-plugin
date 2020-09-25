<?php

namespace Custobar\CustoConnector\Api;

interface MappingDataProviderInterface
{
    /**
     * Returns all mapping data as array keyed by entity types
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface[]
     */
    public function getAllMappingData();

    /**
     * Returns entity specific mapping data by type
     *
     * @param string $entityType
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function getMappingDataByEntityType(string $entityType);

    /**
     * @param string $targetField
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function getMappingDataByTargetField(string $targetField);

    /**
     * Returns entity specific mapping data by entity object
     *
     * @param mixed $entity
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function getMappingDataByObject($entity);
}
