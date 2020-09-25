<?php

namespace Custobar\CustoConnector\Api;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;

interface EntityDataResolverInterface
{
    /**
     * @param ScheduleInterface $schedule
     * @return mixed
     */
    public function resolveEntityBySchedule(ScheduleInterface $schedule);

    /**
     * @param ScheduleInterface[] $schedules
     * @return mixed[]
     */
    public function resolveEntitiesBySchedules(array $schedules);

    /**
     * @param string $entityType
     * @param string $entityId
     * @param int $storeId
     * @return mixed
     */
    public function resolveEntity(string $entityType, string $entityId, int $storeId);

    /**
     * @param string $entityType
     * @param string[] $entityIds
     * @param int $storeId
     * @return mixed[]
     */
    public function resolveEntities(string $entityType, array $entityIds, int $storeId);
}
