<?php

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Custobar\CustoConnector\Model\ScheduleFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;

// Assumes fixture Magento/Catalog/_files/product_simple_multistore.php is used

$objectManager = Bootstrap::getObjectManager();

/** @var Magento\Store\Model\Store $store */
$store = $objectManager->create(Store::class);
$store->load('fixturestore', 'code');

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()
    ->addFieldToFilter(
        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
        Product::class
    )
    ->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}

/** @var ScheduleFactory $scheduleFactory */
$scheduleFactory = $objectManager->create(ScheduleFactory::class);

/** @var ProductFactory $productFactory */
$productFactory = $objectManager->create(ProductFactory::class);

$allScheduleData = [
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $productFactory->create()
            ->loadByAttribute('sku', 'simple')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Product::class,
        ScheduleInterface::STORE_ID => 1,
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
    [
        ScheduleInterface::SCHEDULED_ENTITY_ID => $productFactory->create()
            ->loadByAttribute('sku', 'simple')->getId(),
        ScheduleInterface::SCHEDULED_ENTITY_TYPE => Product::class,
        ScheduleInterface::STORE_ID => $store->getId(),
        ScheduleInterface::PROCESSED_AT => '0000-00-00 00:00:00',
    ],
];

foreach ($allScheduleData as $scheduleData) {
    $scheduleFactory->create()
        ->setData($scheduleData)
        ->save();
}
