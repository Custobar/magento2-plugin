<?php

use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\InitialFactory;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Newsletter\Model\Subscriber;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var InitialFactory $initialFactory */
$initialFactory = $objectManager->create(InitialFactory::class);

$productInitial = $initialFactory->create()
    ->setEntityType(Product::class)
    ->setPage(1)
    ->setPages(2)
    ->setCreatedAt(\time())
    ->setStatus(Status::STATUS_RUNNING);
$productInitial->save();

$customerInitial = $initialFactory->create()
    ->setEntityType(Customer::class)
    ->setPage(1)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setProcessedAt(\time())
    ->setStatus(Status::STATUS_PROCESSED);
$customerInitial->save();

$orderInitial = $initialFactory->create()
    ->setEntityType(Order::class)
    ->setPage(1)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setProcessedAt(\time())
    ->setStatus(Status::STATUS_IDLE);
$orderInitial->save();

$subscriberInitial = $initialFactory->create()
    ->setEntityType(Subscriber::class)
    ->setPage(0)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setStatus(Status::STATUS_RUNNING);
$subscriberInitial->save();

$storeInitial = $initialFactory->create()
    ->setEntityType(Store::class)
    ->setPage(0)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setStatus(Status::STATUS_IDLE);
$storeInitial->save();
