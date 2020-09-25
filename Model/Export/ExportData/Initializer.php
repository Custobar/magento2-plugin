<?php

namespace Custobar\CustoConnector\Model\Export\ExportData;

use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponentInterface;
use Custobar\CustoConnector\Model\Export\ExportDataFactory;
use Custobar\CustoConnector\Api\Data\ExportDataInterface;

class Initializer implements InitializerInterface
{
    /**
     * @var ExportDataFactory
     */
    private $exportDataFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var InitializerComponentInterface[]
     */
    private $components;

    /**
     * @param ExportDataFactory $exportDataFactory
     * @param LoggerInterface $logger
     * @param InitializerComponentInterface[] $components
     */
    public function __construct(
        ExportDataFactory $exportDataFactory,
        LoggerInterface $logger,
        array $components = []
    ) {
        $this->exportDataFactory = $exportDataFactory;
        $this->logger = $logger;
        $this->components = $components;
    }

    /**
     * @inheritDoc
     */
    public function initializeBySchedules(array $schedules)
    {
        $schedulesByType = $this->groupSchedulesByEntityType($schedules);

        $allSyncData = [];
        foreach ($schedulesByType as $entityType => $schedules) {
            /** @var ExportDataInterface $exportData */
            $exportData = $this->exportDataFactory->create();
            $exportData->setEntityType($entityType);
            $exportData->setAllSchedules($schedules);

            try {
                foreach ($this->components as $component) {
                    if (!($component instanceof InitializerComponentInterface)) {
                        continue;
                    }

                    $exportData = $component->execute($exportData);
                }

                $allSyncData[$entityType] = $exportData;
            } catch (\Exception $e) {
                $this->logger->debug(\__(
                    'Failed to construct export data for \'%1\': %2',
                    $entityType,
                    $e->getMessage()
                ), [
                    'trace' => $e->getTrace(),
                ]);

                continue;
            }
        }

        return $allSyncData;
    }

    /**
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface[] $schedules
     * @return mixed[]
     */
    private function groupSchedulesByEntityType(array $schedules)
    {
        $grouped = [];
        foreach ($schedules as $schedule) {
            $grouped[$schedule->getScheduledEntityType()][$schedule->getScheduleId()] = $schedule;
        }

        return $grouped;
    }
}
