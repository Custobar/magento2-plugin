<?php

use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\InitialFactory;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var InitialFactory $initialFactory */
$initialFactory = $objectManager->create(InitialFactory::class);

$productInitial = $initialFactory->create()
    ->setEntityType(Product::class)
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(Status::STATUS_RUNNING);
$productInitial->save();

$customerInitial = $initialFactory->create()
    ->setEntityType(Customer::class)
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(Status::STATUS_RUNNING);
$customerInitial->save();
