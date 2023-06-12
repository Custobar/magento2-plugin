<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Custobar\CustoConnector\Model\ScheduleFactory;
use Magento\Customer\Model\Customer;
use Magento\TestFramework\Helper\Bootstrap;

// Assumes fixture Magento/Customer/_files/three_customers.php is used

$objectManager = Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        Customer::class
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(ScheduleFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Customer::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT - 1,
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 2,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Customer::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
        ScheduleInterface::ERROR_COUNT => 5000,
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => 3,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Customer::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '2020-01-01 00:00:00',
        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
