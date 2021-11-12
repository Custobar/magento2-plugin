<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        \Magento\Sales\Model\Order::class
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}
