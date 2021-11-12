<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\InitialRunner;
use Custobar\CustoConnector\Model\Initial\Populator;
use Custobar\CustoConnector\Model\InitialRepository;
use Custobar\CustoConnector\Model\ResourceModel\Initial;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use \Magento\TestFramework\Helper\Bootstrap;

class InitialRunnerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var InitialRepository
     */
    private $initialRepository;

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
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->initialRepository = $this->objectManager->get(InitialRepository::class);
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
     */
    public function testRunInitialProduct()
    {
        $this->initialResource->removeAll();
        $this->scheduleResource->removeAll();

        $initials = $this->initialPopulator->execute([\Magento\Catalog\Model\Product::ENTITY]);
        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([\Magento\Catalog\Model\Product::ENTITY => 0]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $this->initialRunner->runInitial($initial);
        }
        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 1,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([\Magento\Catalog\Model\Product::ENTITY => 3]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     */
    public function testRunInitialProductPagingProgress()
    {
        $this->initialResource->removeAll();
        $this->scheduleResource->removeAll();

        /** @var Populator $initialPopulator */
        $initialPopulator = $this->objectManager->create(Populator::class, ['pageSize' => 2]);
        $initials = $initialPopulator->execute([\Magento\Catalog\Model\Product::ENTITY]);
        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([\Magento\Catalog\Model\Product::ENTITY => 0]);

        /** @var InitialRunner $initialRunner */
        $initialRunner = $this->objectManager->create(InitialRunner::class, ['pageSize' => 2]);
        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }
        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([\Magento\Catalog\Model\Product::ENTITY => 2]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }
        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([\Magento\Catalog\Model\Product::ENTITY => 3]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Customer/_files/three_customers.php
     * @magentoDataFixture Magento/Sales/_files/order_list.php
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     */
    public function testRunInitialAllPagingProgress()
    {
        $this->initialResource->removeAll();
        $this->scheduleResource->removeAll();

        /** @var Populator $initialPopulator */
        $initialPopulator = $this->objectManager->create(Populator::class, ['pageSize' => 2]);
        $initials = $initialPopulator->execute();
        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Customer\Model\Customer::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Sales\Model\Order::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            'newsletter_subscriber' => [
                InitialInterface::ENTITY_TYPE => 'newsletter_subscriber',
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Store\Model\Store::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
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
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Customer\Model\Customer::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Sales\Model\Order::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            'newsletter_subscriber' => [
                InitialInterface::ENTITY_TYPE => 'newsletter_subscriber',
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::PROCESSED_AT => '0000-00-00 00:00:00',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Store\Model\Store::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([
            \Magento\Catalog\Model\Product::ENTITY => 4,
            \Magento\Customer\Model\Customer::ENTITY => 2,
            \Magento\Sales\Model\Order::ENTITY => 2,
            'newsletter_subscriber' => 2,
            \Magento\Store\Model\Store::ENTITY => 2,
        ]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }

        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            \Magento\Customer\Model\Customer::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            \Magento\Sales\Model\Order::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            'newsletter_subscriber' => [
                InitialInterface::ENTITY_TYPE => 'newsletter_subscriber',
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            \Magento\Store\Model\Store::ENTITY => [
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
                InitialInterface::PAGE => 2,
                InitialInterface::PAGES => 2,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([
            \Magento\Catalog\Model\Product::ENTITY => 8,
            \Magento\Customer\Model\Customer::ENTITY => 3,
            \Magento\Sales\Model\Order::ENTITY => 4,
            'newsletter_subscriber' => 3,
            \Magento\Store\Model\Store::ENTITY => 2,
        ]);

        foreach ($initials as $entityType => $initial) {
            $initials[$entityType] = $initialRunner->runInitial($initial);
        }

        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => null,
            \Magento\Customer\Model\Customer::ENTITY => null,
            \Magento\Sales\Model\Order::ENTITY => null,
            'newsletter_subscriber' => null,
            \Magento\Store\Model\Store::ENTITY => null,
        ];
        $this->assertInitials($expectedData, $initials);
        $this->assertScheduleCounts([
            \Magento\Catalog\Model\Product::ENTITY => 8,
            \Magento\Customer\Model\Customer::ENTITY => 3,
            \Magento\Sales\Model\Order::ENTITY => 4,
            'newsletter_subscriber' => 3,
            \Magento\Store\Model\Store::ENTITY => 2,
        ]);
    }

    /**
     * @param string[] $entityTypes
     * @return \Magento\Framework\DataObject[]|ScheduleInterface[]
     */
    private function getSchedules(array $entityTypes = [])
    {
        $collection = $this->collectionFactory->create()
            ->addOnlyForSendingFilter();
        if (!empty($entityTypes)) {
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
