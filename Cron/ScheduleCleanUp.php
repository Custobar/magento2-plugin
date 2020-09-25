<?php

namespace Custobar\CustoConnector\Cron;

use Custobar\CustoConnector\Model\ResourceModel\Schedule;

class ScheduleCleanUp
{
    /**
     * @var Schedule
     */
    private $scheduleResource;

    public function __construct(
        Schedule $scheduleResource
    ) {
        $this->scheduleResource = $scheduleResource;
    }

    public function execute()
    {
        $this->scheduleResource->removeProcessedSchedules();
    }
}
