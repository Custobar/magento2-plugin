<?php

namespace Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponent;

use Custobar\CustoConnector\Api\EntityDataResolverInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappedDataBuilderInterface;
use Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponentInterface;
use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Magento\Framework\DataObjectFactory;

class AddMappedDataRows implements InitializerComponentInterface
{
    /**
     * @var EntityDataResolverInterface
     */
    private $dataResolver;

    /**
     * @var MappedDataBuilderInterface
     */
    private $mappedDataBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    public function __construct(
        EntityDataResolverInterface $dataResolver,
        MappedDataBuilderInterface $mappedDataBuilder,
        LoggerInterface $logger,
        DataObjectFactory $dataObjectFactory
    ) {
        $this->dataResolver = $dataResolver;
        $this->mappedDataBuilder = $mappedDataBuilder;
        $this->logger = $logger;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExportDataInterface $exportData)
    {
        $schedules = $exportData->getAllSchedules();
        if (empty($exportData->getMappingData())) {
            $allScheduleIds = \array_keys($schedules);

            $exportData->setSuccessfulScheduleIds([]);
            $exportData->setAttemptedScheduleIds($allScheduleIds);
            $exportData->setFailedScheduleIds($allScheduleIds);
            $exportData->setMappedDataRows([]);

            return $exportData;
        }

        $scheduleEntities = $this->dataResolver->resolveEntitiesBySchedules($schedules);

        $entityRows = [];
        $scheduleIds = [];

        /** @var \Custobar\CustoConnector\Api\Data\ScheduleInterface $schedule */
        foreach ($schedules as $schedule) {
            $entity = $scheduleEntities[$schedule->getScheduleId()] ?? null;
            if (empty($entity)) {
                $this->logger->debug(__(
                    'Did not process \'%1\' of \'%2\', entity data not found',
                    $schedule->getScheduledEntityId(),
                    $schedule->getScheduledEntityType()
                ));

                continue;
            }

            $mappedData = $this->mappedDataBuilder->buildMappedData($entity, $schedule->getStoreId());
            if (empty($mappedData)) {
                $this->logger->debug(__(
                    'Did not process \'%1\' of \'%2\', failed to construct data for export',
                    $schedule->getScheduledEntityId(),
                    $schedule->getScheduledEntityType()
                ));

                continue;
            }

            // TODO: Figure out how to implement this properly
            if ($exportData->getEntityType() == \Magento\Sales\Model\Order::class) {
                $itemsData = $mappedData->getData('magento__items') ?? [];
                $mappedData->unsetData('magento__items');
                if (!empty($itemsData)) {
                    $first = \array_shift($itemsData);
                    $mappedData->addData($first);
                }

                foreach ($itemsData as $itemData) {
                    $entityRows[] = $this->dataObjectFactory->create()->setData($itemData);
                }
            }

            $entityRows[] = $mappedData;
            $scheduleIds[] = $schedule->getScheduleId();
        }

        $allScheduleIds = \array_keys($schedules);
        $failedIds = \array_diff($allScheduleIds, $scheduleIds);

        $exportData->setSuccessfulScheduleIds([]);
        $exportData->setAttemptedScheduleIds($scheduleIds);
        $exportData->setFailedScheduleIds($failedIds);
        $exportData->setMappedDataRows($entityRows);

        return $exportData;
    }
}
