<?php

namespace Custobar\CustoConnector\Cron;

use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Magento\Framework\Exception\LocalizedException;

class ScheduleCleanUp
{
    /**
     * @var Schedule
     */
    private $scheduleResource;

    /**
     * @param Schedule $scheduleResource
     */
    public function __construct(
        Schedule $scheduleResource
    ) {
        $this->scheduleResource = $scheduleResource;
    }

    /**
     * Execute cron logic
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->scheduleResource->removeProcessedSchedules();
    }
}
