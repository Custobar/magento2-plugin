<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Api\EntityDataResolverInterface;
use Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponentInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Magento\Framework\Validation\ValidationException;

class EntityDataResolver implements EntityDataResolverInterface
{
    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var ResolverComponentInterface[]
     */
    private $components;

    /**
     * @param MappingDataProviderInterface $mappingDataProvider
     * @param ResolverComponentInterface[] $components
     */
    public function __construct(
        MappingDataProviderInterface $mappingDataProvider,
        array $components = []
    ) {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->components = $components;
    }

    /**
     * @inheritDoc
     */
    public function resolveEntityBySchedule(ScheduleInterface $schedule)
    {
        return $this->resolveEntity(
            $schedule->getScheduledEntityType(),
            $schedule->getScheduledEntityId(),
            $schedule->getStoreId()
        );
    }

    /**
     * @inheritDoc
     */
    public function resolveEntitiesBySchedules(array $schedules)
    {
        $groupedEntityIds = [];
        foreach ($schedules as $schedule) {
            $entityType = $schedule->getScheduledEntityType();
            $storeId = $schedule->getStoreId();
            $scheduleId = $schedule->getScheduleId();
            $entityId = $schedule->getScheduledEntityId();

            $groupedEntityIds[$entityType][$storeId][$scheduleId] = $entityId;
        }

        $entities = [];
        foreach ($groupedEntityIds as $entityType => $storeEntityIds) {
            foreach ($storeEntityIds as $storeId => $entityIds) {
                $resolvedEntities = $this->resolveEntities($entityType, $entityIds, $storeId);
                foreach ($entityIds as $scheduleId => $entityId) {
                    $resolvedEntity = $resolvedEntities[$entityId] ?? null;
                    if (empty($resolvedEntity)) {
                        continue;
                    }

                    $entities[$scheduleId] = $resolvedEntity;
                }
            }
        }

        return $entities;
    }

    /**
     * @inheritDoc
     */
    public function resolveEntity(string $entityType, string $entityId, int $storeId)
    {
        $entities = $this->resolveEntities($entityType, [$entityId], $storeId);

        return $entities[$entityId] ?? false;
    }

    /**
     * @inheritDoc
     */
    public function resolveEntities(string $entityType, array $entityIds, int $storeId)
    {
        $component = $this->components[$entityType] ?? null;
        if ($component === null) {
            return [];
        }
        if (!($component instanceof ResolverComponentInterface)) {
            throw new ValidationException(__('Entity resolver for \'%1\' is not valid', $entityType));
        }

        $mappingData = $this->mappingDataProvider->getMappingDataByEntityType($entityType, $storeId);
        if (!$mappingData) {
            return [];
        }

        return $component->execute($entityIds, $storeId);
    }
}
