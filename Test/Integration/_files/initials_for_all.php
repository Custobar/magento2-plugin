<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Custobar\CustoConnector\Model\InitialFactory $initialFactory */
$initialFactory = $objectManager->create(\Custobar\CustoConnector\Model\InitialFactory::class);

$productInitial = $initialFactory->create()
    ->setEntityType(\Magento\Catalog\Model\Product::ENTITY)
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_RUNNING);
$productInitial->save();

$customerInitial = $initialFactory->create()
    ->setEntityType(\Magento\Customer\Model\Customer::ENTITY)
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_RUNNING);
$customerInitial->save();

$orderInitial = $initialFactory->create()
    ->setEntityType(\Magento\Sales\Model\Order::ENTITY)
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_RUNNING);
$orderInitial->save();

$subscriberInitial = $initialFactory->create()
    ->setEntityType('newsletter_subscriber')
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_RUNNING);
$subscriberInitial->save();

$storeInitial = $initialFactory->create()
    ->setEntityType(\Magento\Store\Model\Store::ENTITY)
    ->setPage(0)
    ->setPage(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_RUNNING);
$storeInitial->save();
