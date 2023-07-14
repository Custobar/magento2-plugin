<?php

use Custobar\CustoConnector\Model\InitialRepository;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var InitialRepository $initialRepository */
$initialRepository = $objectManager->create(InitialRepository::class);

$entityTypes = [
    Product::class,
    Customer::class,
];

foreach ($entityTypes as $entityType) {
    try {
        $initial = $initialRepository->getByEntityType($entityType);
    } catch (NoSuchEntityException $e) {
        continue;
    }

    $initialRepository->delete($initial);
}
