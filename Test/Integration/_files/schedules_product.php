<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

// Assumes fixture Magento/Catalog/_files/products_list.php is used

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        \Magento\Catalog\Model\Product::ENTITY
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var \Custobar\CustoConnector\Model\ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(\Custobar\CustoConnector\Model\ScheduleFactory::class);

/** @var \Magento\Catalog\Model\ProductFactory $productFactory */
$productFactory = $objectManager->create(\Magento\Catalog\Model\ProductFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $productFactory->create()
            ->loadByAttribute('sku', 'wrong-simple')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $productFactory->create()
            ->loadByAttribute('sku', 'simple-249')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $productFactory->create()
            ->loadByAttribute('sku', 'simple-156')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
