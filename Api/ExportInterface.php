<?php

namespace Custobar\CustoConnector\Api;

interface ExportInterface
{
    /**
     * Exports data to Custobar based on the given schedules, returns details of the export
     *
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface[] $schedules
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface[]
     */
    public function exportBySchedules(array $schedules);
}
