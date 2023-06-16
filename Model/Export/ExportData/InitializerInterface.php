<?php

namespace Custobar\CustoConnector\Model\Export\ExportData;

interface InitializerInterface
{
    /**
     * Constructs export data per type
     *
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface[] $schedules
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface[]
     */
    public function initializeBySchedules(array $schedules);
}
