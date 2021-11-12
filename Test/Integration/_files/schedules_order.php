<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

// Assumes fixture Magento/Sales/_files/order_list.php is used

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        \Magento\Sales\Model\Order::ENTITY
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var \Custobar\CustoConnector\Model\ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(\Custobar\CustoConnector\Model\ScheduleFactory::class);

/** @var \Magento\Sales\Model\OrderFactory $orderFactory */
$orderFactory = $objectManager->create(\Magento\Sales\Model\OrderFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $orderFactory->create()
            ->loadByIncrementId('100000002')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $orderFactory->create()
            ->loadByIncrementId('100000004')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
