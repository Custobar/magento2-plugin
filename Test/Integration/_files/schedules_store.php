<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        \Magento\Store\Model\Store::ENTITY
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var \Custobar\CustoConnector\Model\ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(\Custobar\CustoConnector\Model\ScheduleFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
