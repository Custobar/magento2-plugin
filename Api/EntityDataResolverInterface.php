<?php

namespace Custobar\CustoConnector\Api;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;

interface EntityDataResolverInterface
{
    /**
     * Get entity instance based on the schedule
     *
     * @param ScheduleInterface $schedule
     *
     * @return mixed
     */
    public function resolveEntityBySchedule(ScheduleInterface $schedule);

    /**
     * Get entity instances based on multiple schedules
     *
     * @param ScheduleInterface[] $schedules
     *
     * @return mixed[]
     */
    public function resolveEntitiesBySchedules(array $schedules);

    /**
     * Get entity instance based on detailed parameters
     *
     * @param string $entityType
     * @param string $entityId
     * @param int $storeId
     *
     * @return mixed
     */
    public function resolveEntity(string $entityType, string $entityId, int $storeId);

    /**
     * Get entity instances based on detailed parameters
     *
     * @param string $entityType
     * @param string[] $entityIds
     * @param int $storeId
     *
     * @return mixed[]
     */
    public function resolveEntities(string $entityType, array $entityIds, int $storeId);
}
