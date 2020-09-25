<?php

use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->create(CollectionFactory::class);
$schedules = $collectionFactory->create()->getItems();
foreach ($schedules as $schedule) {
    $schedule->delete();
}
