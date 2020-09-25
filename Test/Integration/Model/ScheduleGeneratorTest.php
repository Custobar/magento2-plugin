<?php

namespace Custobar\CustoConnector\Test\Integration\Model;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Custobar\CustoConnector\Model\ScheduleGenerator;
use Magento\Catalog\Model\ProductRepository;
use \Magento\TestFramework\Helper\Bootstrap;

class ScheduleGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var ScheduleGenerator
     */
    private $scheduleGenerator;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->get(ProductRepository::class);
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
        $this->scheduleGenerator = $this->objectManager->get(ScheduleGenerator::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Customer/_files/three_customers.php
     * @magentoDataFixture loadSchedulesCustomerFixture
     */
    public function testSchemaDuplicateKeyRestriction()
    {
        $result = $this->scheduleGenerator->generateByData(
            3,
            1,
            \Magento\Customer\Model\Customer::class
        );

        $this->assertFalse(
            $result,
            'Testing that cant add the same again that has been added from the fixture already'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoDataFixture loadSchedulesCustomerFixture
     */
    public function testAddSchedules()
    {
        $productSchedule = $this->scheduleGenerator->generateByData(
            1,
            1,
            \Magento\Catalog\Model\Product::class
        );

        $this->assertNotFalse(
            $productSchedule,
            'Testing did add a product schedule'
        );

        $schedules = $this->collectionFactory->create();
        $schedules->addOnlyForSendingFilter();

        /** @var ScheduleInterface $firstSchedule */
        $firstSchedule = $schedules->getFirstItem();
        $this->assertEquals(3, $firstSchedule->getScheduledEntityId());
        $this->assertEquals(
            \Magento\Customer\Model\Customer::class,
            $firstSchedule->getScheduledEntityType(),
            'Test that customer model is the first in the schedules'
        );

        $this->assertEquals(
            1,
            $productSchedule->getStoreId(),
            'Test that there is a store id present in the data'
        );

        // stored state count
        $this->assertEquals(
            3,
            $schedules->count(),
            'Test that there are only 3 scheduled items'
        );
    }

    public static function loadSchedulesCustomerFixture()
    {
        include __DIR__ . '/../_files/schedules_customer.php';
    }

    public static function loadSchedulesCustomerFixtureRollback()
    {
        include __DIR__ . '/../_files/schedules_customer_rollback.php';
    }

    public static function loadSchedulesProductFixture()
    {
        include __DIR__ . '/../_files/schedules_product.php';
    }

    public static function loadSchedulesProductFixtureRollback()
    {
        include __DIR__ . '/../_files/schedules_product_rollback.php';
    }
}
