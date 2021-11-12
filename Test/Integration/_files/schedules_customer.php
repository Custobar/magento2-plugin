<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

// Assumes fixture Magento/Customer/_files/three_customers.php is used

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        \Magento\Customer\Model\Customer::ENTITY
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var \Custobar\CustoConnector\Model\ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(\Custobar\CustoConnector\Model\ScheduleFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 2,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '2020-01-01 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 3,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 2,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
