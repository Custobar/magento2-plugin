<?php

namespace Custobar\CustoConnector\Model\Schedule;

interface ExportableProviderInterface
{
    /**
     * Returns all schedules where the data is possible to export to Custobar
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface[]
     */
    public function getSchedulesForExport();
}
