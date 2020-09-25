<?php

namespace Custobar\CustoConnector\Model\Export\ExportData\Processor;

use Custobar\CustoConnector\Model\Export\ExportData\ProcessorInterface;
use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class AdjustSchedules implements ProcessorInterface
{
    /**
     * @var Schedule
     */
    private $scheduleResource;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(
        Schedule $scheduleResource,
        TimezoneInterface $timezone
    ) {
        $this->scheduleResource = $scheduleResource;
        $this->timezone = $timezone;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExportDataInterface $exportData)
    {
        $currentTime = $this->timezone->date()->format('Y-m-d H:i:s');

        if ($exportData->getMappingData() && !empty($exportData->getRequestDataJson())) {
            $failedIds = $exportData->getFailedScheduleIds();
            $successIds = $exportData->getSuccessfulScheduleIds();

            $this->scheduleResource->updateProcessedAt($successIds, $currentTime);

            $this->scheduleResource->increaseErrorCounts($failedIds);
            $this->scheduleResource->updateProcessedAt($failedIds, $currentTime);

            $failedIds = $this->scheduleResource->filterReschedulable($failedIds);
            $this->scheduleResource->updateProcessedAt($failedIds, '0000-00-00 00:00:00');

            return $exportData;
        }

        // Reaching here means the data is not valid at all -> reject entirely
        $scheduleIds = \array_keys($exportData->getAllSchedules());
        $this->scheduleResource->increaseErrorCounts($scheduleIds, Schedule::MAX_ERROR_COUNT);
        $this->scheduleResource->updateProcessedAt($scheduleIds, $currentTime);

        return $exportData;
    }
}
