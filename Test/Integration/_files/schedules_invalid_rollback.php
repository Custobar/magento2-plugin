<?php

use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}
