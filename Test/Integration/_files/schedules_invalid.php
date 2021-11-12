<?php

// Should work nicely with Magento/Customer/_files/customer_one_address.php fixture

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(ScheduleInterface::SCHEDULED_ENTITY_TYPE, [
        'unknown_type',
        \Magento\Catalog\Model\Product::ENTITY,
        \Magento\Customer\Model\Customer::ENTITY,
        'customer_address',
    ])
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var \Custobar\CustoConnector\Model\ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(\Custobar\CustoConnector\Model\ScheduleFactory::class);

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
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        // Non existing store
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
        ScheduleInterface::STORE_ID => 1000,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        // Unmapped type
        ScheduleInterface::SCHEDULED_ENTITY_ID => 1,
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => 'customer_address',
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
