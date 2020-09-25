<?php

namespace Custobar\CustoConnector\Api;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;

interface ScheduleRepositoryInterface
{
    /**
     * @param int $scheduleId
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function get(int $scheduleId);

    /**
     * @param string $entityType
     * @param int $entityId
     * @param int $storeId
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function getByData(string $entityType, int $entityId, int $storeId);

    /**
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface $schedule
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function save(ScheduleInterface $schedule);

    /**
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface $schedule
     * @return bool
     */
    public function delete(ScheduleInterface $schedule);
}
