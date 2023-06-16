<?php

namespace Custobar\CustoConnector\Api;

interface ScheduleGeneratorInterface
{
    /**
     * Check if schedule creation is necessary and only then generate schedule entity based on given parameters
     *
     * @param int $entityId
     * @param int $storeId
     * @param string $entityType
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface|bool
     */
    public function generateByData(int $entityId, int $storeId, string $entityType);

    /**
     * Create multiple schedules based on store ids available in the entity by calling generateByData()
     *
     * @param mixed $entity
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface[]|bool[]
     */
    public function generateByEntity($entity);
}
