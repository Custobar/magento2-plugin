<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\InitialRunner;
use Custobar\CustoConnector\Model\Initial\Populator;
use Custobar\CustoConnector\Model\ResourceModel\Initial;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Framework\DataObject;
use Magento\Newsletter\Model\Subscriber;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InitialRunnerTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Populator
     */
    private $initialPopulator;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Schedule
     */
    private $scheduleResource;

    /**
     * @var Initial
     */
    private $initialResource;

    /**
     * @var InitialRunner
     */
    private $initialRunner;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->initialPopulator = $this->objectManager->get(Populator::class);
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
        $this->scheduleResource = $this->objectManager->get(Schedule::class);
        $this->initialResource = $this->objectManager->get(Initial::class);
        $this->initialRunner = $this->objectManager->get(InitialRunner::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testRunInitialProduct()
    {
        $this->initialResource->removeAll();
        $this->scheduleResource->removeAll();

        $initials = $this->initialPopulator->execute([Product::class]);
        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([Product::class => 0]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $this->initialRunner->runInitial($initial);
        }
        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 1,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([Product::class => 3]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testRunInitialProductPagingProgress()
    {
        $this->initialResource->removeAll();
        $this->scheduleResource->removeAll();

        /** @var Populator $initialPopulator */
        $initialPopulator = $this->objectManager->create(Populator::class, ['pageSize' => 2]);
        $initials = $initialPopulator->execute([Product::class]);
        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([Product::class => 0]);

        /** @var InitialRunner $initialRunner */
        $initialRunner = $this->objectManager->create(InitialRunner::class, ['pageSize' => 2]);
        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }
        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([Product::class => 2]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }
        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([Product::class => 3]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixtureBeforeTransaction Magento/Customer/_files/three_customers.php
     * @magentoDataFixtureBeforeTransaction Magento/Sales/_files/order_list.php
     * @magentoDataFixtureBeforeTransaction Magento/Newsletter/_files/subscribers.php
     * @magentoDataFixtureBeforeTransaction Magento/Catalog/_files/products_list.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testRunInitialAllPagingProgress()
    {
        $this->initialResource->removeAll();
        $this->scheduleResource->removeAll();

        /** @var Populator $initialPopulator */
        $initialPopulator = $this->objectManager->create(Populator::class, ['pageSize' => 2]);
        $initials = $initialPopulator->execute();
        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Customer::class => [
                InitialInterface::ENTITY_TYPE => Customer::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Order::class => [
                InitialInterface::ENTITY_TYPE => Order::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Subscriber::class => [
                InitialInterface::ENTITY_TYPE => Subscriber::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Store::class => [
                InitialInterface::ENTITY_TYPE => Store::class,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([]);

        /** @var InitialRunner $initialRunner */
        $initialRunner = $this->objectManager->create(InitialRunner::class, ['pageSize' => 2]);
        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }

        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Customer::class => [
                InitialInterface::ENTITY_TYPE => Customer::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Order::class => [
                InitialInterface::ENTITY_TYPE => Order::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Subscriber::class => [
                InitialInterface::ENTITY_TYPE => Subscriber::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Store::class => [
                InitialInterface::ENTITY_TYPE => Store::class,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([
            Product::class => 4,
            Customer::class => 2,
            Order::class => 2,
            Subscriber::class => 2,
            Store::class => 2,
        ]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }

        $expectedData = [
            Product::class => [
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            Customer::class => [
                InitialInterface::ENTITY_TYPE => Customer::class,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            Order::class => [
                InitialInterface::ENTITY_TYPE => Order::class,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            Subscriber::class => [
                InitialInterface::ENTITY_TYPE => Subscriber::class,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            Store::class => [
                InitialInterface::ENTITY_TYPE => Store::class,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([
            Product::class => 8,
            Customer::class => 3,
            Order::class => 4,
            Subscriber::class => 3,
            Store::class => 2,
        ]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }

        $expectedData = [
            Product::class => null,
            Customer::class => null,
            Order::class => null,
            Subscriber::class => null,
            Store::class => null,
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([
            Product::class => 8,
            Customer::class => 3,
            Order::class => 4,
            Subscriber::class => 3,
            Store::class => 2,
        ]);
    }

    /**
     * @param string[] $entityTypes
     *
     * @return DataObject[]|ScheduleInterface[]
     */
    private function getSchedules(array $entityTypes = [])
    {
        $collection = $this->collectionFactory->create()
            ->addOnlyForSendingFilter();
        if ($entityTypes) {
            $collection->addFieldToFilter(
                ScheduleInterface::SCHEDULED_ENTITY_TYPE,
                ['in' => $entityTypes]
            );
        }

        return $collection->getItems();
    }

    /**
     * @param mixed[] $allExpectedData
     * @param InitialInterface[] $initials
     */
    private function assertInitials(array $allExpectedData, array $initials)
    {
        $this->assertEquals(\count($allExpectedData), \count($initials));

        foreach ($allExpectedData as $entityType => $expectedData) {
            $initial = $initials[$entityType];
            if (!\is_array($expectedData)) {
                $this->assertEquals($expectedData, $initial);
                continue;
            }
            foreach ($expectedData as $field => $expectedValue) {
                $realValue = $initial->getData($field);
                $this->assertEquals($expectedValue, $realValue, \sprintf(
                    'Assert that %s initial\'s %s matches the expected value',
                    $entityType,
                    $field
                ));
            }
        }
    }

    /**
     * @param int[] $expectedCounts
     */
    private function assertScheduleCounts(array $expectedCounts)
    {
        $totalQty = 0;
        foreach ($expectedCounts as $entityType => $qty) {
            $schedules = $this->getSchedules([$entityType]);
            $this->assertEquals($qty, \count($schedules), \sprintf(
                'Assert that number of %s schedules matches the expected amount',
                $entityType
            ));
            $totalQty += $qty;
        }

        $allSchedules = $this->getSchedules();
        $this->assertEquals($totalQty, \count($allSchedules), 'Assert that total number of schedules matches');
    }
}
