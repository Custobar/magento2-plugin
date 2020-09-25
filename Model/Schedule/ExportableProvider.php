<?php

namespace Custobar\CustoConnector\Model\Schedule;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\Collection;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

class ExportableProvider implements ExportableProviderInterface
{
    const DEFAULT_LIMIT = 500;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var int
     */
    private $scheduleLimit;

    public function __construct(
        CollectionFactory $collectionFactory,
        int $scheduleLimit = self::DEFAULT_LIMIT
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->scheduleLimit = $scheduleLimit;
    }

    /**
     * @inheritDoc
     */
    public function getSchedulesForExport()
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create()
            ->addOnlyForSendingFilter()
            ->setOrder(ScheduleInterface::CREATED_AT, Collection::SORT_ORDER_ASC)
            ->setOrder(ScheduleInterface::SCHEDULED_ENTITY_TYPE, Collection::SORT_ORDER_ASC)
            ->setPageSize($this->scheduleLimit)
            ->setCurPage(1);

        return $collection->getItems();
    }
}
