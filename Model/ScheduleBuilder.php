<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\ScheduleBuilderInterface;
use Magento\Framework\Exception\LocalizedException;

class ScheduleBuilder implements ScheduleBuilderInterface
{
    /**
     * @var ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

    /**
     * @param ScheduleFactory $scheduleFactory
     * @param EntityTypeResolverInterface $typeResolver
     */
    public function __construct(
        ScheduleFactory $scheduleFactory,
        EntityTypeResolverInterface $typeResolver
    ) {
        $this->scheduleFactory = $scheduleFactory;
        $this->typeResolver = $typeResolver;
    }

    /**
     * @inheritDoc
     */
    public function buildByData(array $scheduleData)
    {
        /** @var ScheduleInterface $schedule */
        $schedule = $this->scheduleFactory->create();

        $storeId = $scheduleData[ScheduleInterface::STORE_ID] ?? 0;
        $schedule->setStoreId((int)$storeId);

        $entityId = $scheduleData[ScheduleInterface::SCHEDULED_ENTITY_ID] ?? 0;
        if (!$entityId) {
            throw new LocalizedException(__(
                'Field \'%1\' is required',
                ScheduleInterface::SCHEDULED_ENTITY_ID
            ));
        }
        $schedule->setScheduledEntityId((int)$entityId);

        $entityType = $scheduleData[ScheduleInterface::SCHEDULED_ENTITY_TYPE] ?? null;
        if (!$entityType) {
            throw new LocalizedException(__(
                'Field \'%1\' is required',
                ScheduleInterface::SCHEDULED_ENTITY_TYPE
            ));
        }
        $schedule->setScheduledEntityType((string)$entityType);

        $processedAt = $scheduleData[ScheduleInterface::PROCESSED_AT] ?? '';
        $schedule->setProcessedAt((string)$processedAt);

        $errorCount = $scheduleData[ScheduleInterface::ERROR_COUNT] ?? '';
        $schedule->setErrorCount((int)$errorCount);

        return $schedule;
    }

    /**
     * @inheritDoc
     */
    public function buildByEntity($entity)
    {
        $entityType = $this->typeResolver->resolveEntityType($entity);

        return $this->buildByData([
            ScheduleInterface::STORE_ID => $entity->getStoreId(),
            ScheduleInterface::SCHEDULED_ENTITY_ID => $entity->getId(),
            ScheduleInterface::SCHEDULED_ENTITY_TYPE => $entityType,
            ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
            ScheduleInterface::ERROR_COUNT => 0,
        ]);
    }
}
