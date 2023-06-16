<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Custobar\CustoConnector\Model\ScheduleFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;

// Assumes fixture Magento/Sales/_files/order_list.php is used

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        Order::class
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(ScheduleFactory::class);

/** @var OrderFactory $orderFactory */
$orderFactory = $objectManager->create(OrderFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $orderFactory->create()
            ->loadByIncrementId('100000002')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Order::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $orderFactory->create()
            ->loadByIncrementId('100000004')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Order::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
