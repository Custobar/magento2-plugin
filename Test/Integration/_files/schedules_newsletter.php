<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Custobar\CustoConnector\Model\ScheduleFactory;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;

// Assumes fixture Magento/Newsletter/_files/subscribers.php is used

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        Subscriber::class
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(ScheduleFactory::class);

/** @var SubscriberFactory $subscriberFactory */
$subscriberFactory = $objectManager->create(SubscriberFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $subscriberFactory->create()
            ->loadByEmail('customer@example.com')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Subscriber::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $subscriberFactory->create()
            ->loadByEmail('customer_two@example.com')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Subscriber::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $subscriberFactory->create()
            ->loadByEmail('customer_confirm@example.com')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Subscriber::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
