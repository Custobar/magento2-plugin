<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Custobar\CustoConnector\Model\InitialRepository $initialRepository */
$initialRepository = $objectManager->create(\Custobar\CustoConnector\Model\InitialRepository::class);

$entityTypes = [
    \Magento\Catalog\Model\Product::ENTITY,
    \Magento\Customer\Model\Customer::ENTITY,
];

foreach ($entityTypes as $entityType) {
    try {
        $initial = $initialRepository->getByEntityType($entityType);
    } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
        continue;
    }

    $initialRepository->delete($initial);
}
