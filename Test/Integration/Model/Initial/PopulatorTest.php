<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\Populator;
use Custobar\CustoConnector\Model\InitialRepository;
use Custobar\CustoConnector\Model\MappingDataProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use \Magento\TestFramework\Helper\Bootstrap;

class PopulatorTest extends \PHPUnit\Framework\TestCase
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
    protected function setUp()
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
            \Magento\Catalog\Model\Product::ENTITY => [],
            \Magento\Customer\Model\Customer::ENTITY => [],
            \Magento\Sales\Model\Order::ENTITY => [],
            'newsletter_subscriber' => [],
            \Magento\Store\Model\Store::ENTITY => [],
        ];

        $initials = $this->getInitials(\array_keys($originalData));
        $this->assertEmpty($initials);
        $this->assertInitials($originalData, $initials);

        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Customer\Model\Customer::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Sales\Model\Order::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            'newsletter_subscriber' => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => 'newsletter_subscriber',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Store\Model\Store::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
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
     * @magentoDataFixture loadInitialsWithMixedStatusFixture
     */
    public function testExecuteOnExisting()
    {
        $originalData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 2,
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Customer\Model\Customer::ENTITY => [
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
                InitialInterface::STATUS => Status::STATUS_PROCESSED,
            ],
            \Magento\Sales\Model\Order::ENTITY => [
                InitialInterface::PAGE => 1,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
                InitialInterface::STATUS => Status::STATUS_IDLE,
            ],
            'newsletter_subscriber' => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => 'newsletter_subscriber',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Store\Model\Store::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
                InitialInterface::STATUS => Status::STATUS_IDLE,
            ],
        ];

        $initials = $this->getInitials(\array_keys($originalData));
        $this->assertInitials($originalData, $initials);

        $expectedData = [
            \Magento\Catalog\Model\Product::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Customer\Model\Customer::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Sales\Model\Order::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            'newsletter_subscriber' => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => 'newsletter_subscriber',
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Store\Model\Store::ENTITY => [
                InitialInterface::PAGE => 0,
                InitialInterface::PAGES => 1,
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::ENTITY,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ];

        $initials = $this->populator->execute(\array_keys($expectedData));
        $this->assertInitials($expectedData, $initials);
    }

    /**
     * @param string[] $entityTypes
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
            if (!empty($expectedData)) {
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

    public static function loadInitialsWithMixedStatusFixture()
    {
        include __DIR__ . '/../../_files/initials_with_mixed_status.php';
    }

    public static function loadInitialsWithMixedStatusFixtureRollback()
    {
        include __DIR__ . '/../../_files/initials_with_mixed_status_rollback.php';
    }
}
