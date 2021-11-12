<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

// Assumes fixture Magento/Newsletter/_files/subscribers.php is used

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        'newsletter_subscriber'
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var \Custobar\CustoConnector\Model\ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(\Custobar\CustoConnector\Model\ScheduleFactory::class);

/** @var \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory */
$subscriberFactory = $objectManager->create(\Magento\Newsletter\Model\SubscriberFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $subscriberFactory->create()
            ->loadByEmail('customer@example.com')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => 'newsletter_subscriber',
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $subscriberFactory->create()
            ->loadByEmail('customer_two@example.com')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => 'newsletter_subscriber',
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $subscriberFactory->create()
            ->loadByEmail('customer_confirm@example.com')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => 'newsletter_subscriber',
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
