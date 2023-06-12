<?php

// Should work nicely with Magento/Customer/_files/customer_one_address.php fixture

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Custobar\CustoConnector\Model\ScheduleFactory;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Customer;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(ScheduleInterface::SCHEDULED_ENTITY_TYPE, [
        'unknown_type',
        Product::class,
        Customer::class,
        Address::class,
    ])
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(ScheduleFactory::class);

$allScheduleData = [
    [
        // Unknown/unmapped type
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => 'unknown_type',
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        // Non existing entity
        ScheduleInterface::SCHEDULED_ENTITY_ID => 99999,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Product::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        // Non existing store
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Customer::class,
        ScheduleInterface::STORE_ID => 1000,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        // Unmapped type
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Address::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
