<?php

namespace Custobar\CustoConnector\Api;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;

interface ScheduleRepositoryInterface
{
    /**
     * Get schedule entity by its entity id
     *
     * @param int $scheduleId
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function get(int $scheduleId);

    /**
     * Get schedule entity by detailed parameters
     *
     * @param string $entityType
     * @param int $entityId
     * @param int $storeId
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function getByData(string $entityType, int $entityId, int $storeId);

    /**
     * Save schedule entity
     *
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface $schedule
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function save(ScheduleInterface $schedule);

    /**
     * Delete schedule entity
     *
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface $schedule
     *
     * @return bool
     */
    public function delete(ScheduleInterface $schedule);
}
