<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Custobar\CustoConnector\Model\InitialFactory $initialFactory */
$initialFactory = $objectManager->create(\Custobar\CustoConnector\Model\InitialFactory::class);

$productInitial = $initialFactory->create()
    ->setEntityType(\Magento\Catalog\Model\Product::ENTITY)
    ->setPage(2)
    ->setPages(2)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_PROCESSED);
$productInitial->save();

$customerInitial = $initialFactory->create()
    ->setEntityType(\Magento\Customer\Model\Customer::ENTITY)
    ->setPage(1)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setProcessedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_PROCESSED);
$customerInitial->save();

$orderInitial = $initialFactory->create()
    ->setEntityType(\Magento\Sales\Model\Order::ENTITY)
    ->setPage(1)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setProcessedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_IDLE);
$orderInitial->save();

$subscriberInitial = $initialFactory->create()
    ->setEntityType('newsletter_subscriber')
    ->setPage(0)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_IDLE);
$subscriberInitial->save();

$storeInitial = $initialFactory->create()
    ->setEntityType(\Magento\Store\Model\Store::ENTITY)
    ->setPage(0)
    ->setPages(1)
    ->setCreatedAt(\time())
    ->setStatus(\Custobar\CustoConnector\Model\Initial\Config\Source\Status::STATUS_IDLE);
$storeInitial->save();
