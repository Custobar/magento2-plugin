<?php

use Custobar\CustoConnector\Model\InitialRepository;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Newsletter\Model\Subscriber;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var InitialRepository $initialRepository */
$initialRepository = $objectManager->create(InitialRepository::class);

$entityTypes = [
    Product::class,
    Customer::class,
    Order::class,
    Subscriber::class,
    Store::class,
];

foreach ($entityTypes as $entityType) {
    try {
        $initial = $initialRepository->getByEntityType($entityType);
    } catch (NoSuchEntityException $e) {
        continue;
    }

    $initialRepository->delete($initial);
}
