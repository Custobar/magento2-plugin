<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\ScheduleBuilderInterface;
use Custobar\CustoConnector\Api\ScheduleGeneratorInterface;
use Custobar\CustoConnector\Api\ScheduleRepositoryInterface;
use \Custobar\CustoConnector\Model\ResourceModel\Schedule as ScheduleResource;

class ScheduleGenerator implements ScheduleGeneratorInterface
{
    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * @var ScheduleBuilderInterface
     */
    private $scheduleBuilder;

    /**
     * @var ScheduleResource
     */
    private $scheduleResource;

    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        ScheduleBuilderInterface $scheduleBuilder,
        ScheduleResource $scheduleResource,
        EntityTypeResolverInterface $typeResolver
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleBuilder = $scheduleBuilder;
        $this->scheduleResource = $scheduleResource;
        $this->typeResolver = $typeResolver;
    }

    /**
     * @inheritDoc
     */
    public function generateByData(int $entityId, int $storeId, string $entityType)
    {
        $existingId = $this->scheduleResource->getExistingId($entityType, $entityId, $storeId);
        if ($existingId > 0) {
            return false; // For now respect the previous flow so only generate once
        }

        $schedule = $this->scheduleBuilder->buildByData([
            ScheduleInterface::SCHEDULED_ENTITY_TYPE => $entityType,
            ScheduleInterface::SCHEDULED_ENTITY_ID => $entityId,
            ScheduleInterface::STORE_ID => $storeId,
            ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
            ScheduleInterface::ERROR_COUNT => 0,
        ]);

        return $this->scheduleRepository->save($schedule);
    }

    /**
     * @inheritDoc
     */
    public function generateByEntity($entity)
    {
        $entityType = $this->typeResolver->resolveEntityType($entity);

        $storeId = $entity->getStoreId();
        $storeIds = $entity->getStoreIds() ?? [];
        if (empty($storeIds) && $storeId !== null) {
            $storeIds[] = $storeId;
        }

        $generated = [];
        foreach ($storeIds as $storeId) {
            $generated[$storeId] = $this->generateByData(
                (int)$entity->getId(),
                (int)$storeId,
                (string)$entityType
            );
        }

        return $generated;
    }
}
