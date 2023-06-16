<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\Populator;
use Custobar\CustoConnector\Model\InitialRepository;
use Custobar\CustoConnector\Model\MappingDataProvider;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Newsletter\Model\Subscriber;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PopulatorTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var InitialRepository
     */
    private $initialRepository;

    /**
     * @var MappingDataProvider
     */
    private $mappingDataProvider;

    /**
     * @var Populator
     */
    private $populator;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->initialRepository = $this->objectManager->get(InitialRepository::class);
        $this->mappingDataProvider = $this->objectManager->get(MappingDataProvider::class);
        $this->populator = $this->objectManager->get(Populator::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoDataFixture Magento/Customer/_files/three_customers.php
     * @magentoDataFixture Magento/Sales/_files/order_list.php
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     */
    public function testExecute()
    {
        $originalData = [
            Product::class => [],
            Customer::class => [],
            Order::class => [],
            Subscriber::class => [],
            Store::class => [],
        ];

        $initials = $this->getInitials(\array_keys($originalData));
        $this->assertEmpty($initials);
        $this->assertInitials($originalData, $initials);

        $expectedData = [
            Product::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Customer::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Customer::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Order::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Order::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Subscriber::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Subscriber::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Store::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Store::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];

        $initials = $this->getInitials(\array_keys($expectedData));
        $this->assertEmpty($initials);
        $initials = $this->populator->execute([]);
        $this->assertInitials($expectedData, $initials);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoDataFixture Magento/Customer/_files/three_customers.php
     * @magentoDataFixture Magento/Sales/_files/order_list.php
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/initials_with_mixed_status.php
     */
    public function testExecuteOnExisting()
    {
        $originalData = [
            Product::class => [
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Customer::class => [
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Customer::class,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            Order::class => [
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Order::class,
                InitialInterface::STATUS => Status::STATUS_IDLE,
            ],
            Subscriber::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Subscriber::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Store::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Store::class,
                InitialInterface::STATUS => Status::STATUS_IDLE,
            ],
        ];

        $initials = $this->getInitials(\array_keys($originalData));
        $this->assertInitials($originalData, $initials);

        $expectedData = [
            Product::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Product::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Customer::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Customer::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Order::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Order::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Subscriber::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Subscriber::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            Store::class => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => Store::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];

        $initials = $this->populator->execute(\array_keys($expectedData));
        $this->assertInitials($expectedData, $initials);
    }

    /**
     * @param string[] $entityTypes
     *
     * @return InitialInterface[]
     */
    private function getInitials(array $entityTypes)
    {
        $initials = [];
        foreach ($entityTypes as $entityType) {
            try {
                $initial = $this->initialRepository->getByEntityType($entityType);
            } catch (NoSuchEntityException $e) {
                continue;
            }

            $initials[$entityType] = $initial;
        }

        return $initials;
    }

    /**
     * @param mixed[] $allExpectedData
     * @param InitialInterface[] $initials
     *
     * @return void
     */
    private function assertInitials(array $allExpectedData, array $initials)
    {
        $allMappingData = $this->mappingDataProvider->getAllMappingData();
        $this->assertNotEmpty($allMappingData);

        foreach ($allMappingData as $mappingData) {
            $entityType = $mappingData->getEntityType();

            $initial = $initials[$entityType] ?? null;
            $expectedData = $allExpectedData[$entityType] ?? [];
            if ($expectedData) {
                $this->assertNotNull(
                    $initial,
                    'Assert that initial for ' . $entityType . ' exists'
                );

                foreach ($expectedData as $field => $expectedValue) {
                    $realValue = $initial->getData($field);
                    $this->assertEquals(
                        $expectedValue,
                        $realValue,
                        \sprintf(
                            'Assert that %s initial\'s %s matches expected value',
                            $entityType,
                            $field
                        )
                    );
                }

                continue;
            }

            $this->assertNull(
                $initial,
                'Assert that initial for ' . $entityType . ' does not exists'
            );
        }
    }
}
