<?php

namespace Custobar\CustoConnector\Api;

interface ScheduleGeneratorInterface
{
    /**
     * @param int $entityId
     * @param int $storeId
     * @param string $entityType
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface|bool
     */
    public function generateByData(int $entityId, int $storeId, string $entityType);

    /**
     * @param mixed $entity
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface[]|bool[]
     */
    public function generateByEntity($entity);
}
