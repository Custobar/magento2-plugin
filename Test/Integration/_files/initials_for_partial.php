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
