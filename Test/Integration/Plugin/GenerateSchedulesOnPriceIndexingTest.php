<?php

namespace Custobar\CustoConnector\Test\Integration\Plugin;

use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\ScheduleRepository;
use Magento\Catalog\Cron\RefreshSpecialPrices;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManager;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GenerateSchedulesOnPriceIndexingTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var Schedule
     */
    private $scheduleResource;

    /**
     * @var TimezoneInterface|MockObject
     */
    private $localeDateMock;

    /**
     * @var RefreshSpecialPrices
     */
    private $refreshSpecialPrices;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->get(ProductRepository::class);
        $this->scheduleRepository = $this->objectManager->get(ScheduleRepository::class);
        $this->storeManager = $this->objectManager->get(StoreManager::class);
        $this->scheduleResource = $this->objectManager->get(Schedule::class);
        $this->localeDateMock = $this->createMock(TimezoneInterface::class);
        $this->refreshSpecialPrices = $this->objectManager->create(RefreshSpecialPrices::class, [
            'localeDate' => $this->localeDateMock,
        ]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Catalog/_files/product_simple_with_all_fields.php
     */
    public function testExecuteShouldNotScheduleWhenRefreshSpecialPricesDoesNothing()
    {
        $timestamp = \date('Y-m-d 00:00', \time());
        $timestamp = \strtotime($timestamp);
        $this->localeDateMock->expects($this->any())->method('scopeTimeStamp')
            ->willReturn($timestamp);

        $this->scheduleResource->removeAll();
        $product = $this->productRepository->get('simple');
        $storeId = $this->storeManager->getStore()->getId();

        try {
            $this->scheduleRepository->getByData(
                Product::class,
                $product->getId(),
                $storeId
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $this->refreshSpecialPrices->execute();

        $this->expectException(NoSuchEntityException::class);
        $this->scheduleRepository->getByData(
            Product::class,
            $product->getId(),
            $storeId
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Catalog/_files/product_simple_with_all_fields.php
     */
    public function testExecuteShouldScheduleWhenRefreshSpecialPricesUpdatesPastPrice()
    {
        $timestamp = \date('Y-m-d 00:00', \strtotime('+2 day'));
        $timestamp = \strtotime($timestamp);
        $this->localeDateMock->expects($this->any())->method('scopeTimeStamp')
            ->willReturn($timestamp);

        $this->scheduleResource->removeAll();
        $product = $this->productRepository->get('simple');
        $storeId = $this->storeManager->getStore()->getId();

        try {
            $this->scheduleRepository->getByData(
                Product::class,
                $product->getId(),
                $storeId
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $this->refreshSpecialPrices->execute();

        $schedule = $this->scheduleRepository->getByData(
            Product::class,
            $product->getId(),
            $storeId
        );

        $this->assertEquals($product->getId(), $schedule->getScheduledEntityId());
        $this->assertEquals(Product::class, $schedule->getScheduledEntityType());
        $this->assertEquals($storeId, $schedule->getStoreId());
    }
}
