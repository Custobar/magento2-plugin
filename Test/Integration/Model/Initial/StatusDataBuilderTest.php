<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Initial;

use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilder;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;
use Custobar\CustoConnector\Model\MappingDataProvider;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Newsletter\Model\Subscriber;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class StatusDataBuilderTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var MappingDataProvider
     */
    private $mappingDataProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var StatusDataBuilder
     */
    private $statusDataBuilder;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->mappingDataProvider = $this->objectManager->get(MappingDataProvider::class);
        $this->statusDataBuilder = $this->objectManager->get(StatusDataBuilder::class);
        $this->urlBuilder = $this->objectManager->get(UrlInterface::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/initials_with_mixed_status.php
     */
    public function testBuildByMappingData()
    {
        $allStatusData = [];
        $allMappingData = $this->mappingDataProvider->getAllMappingData();
        foreach ($allMappingData as $mappingData) {
            $statusData = $this->statusDataBuilder->buildByMappingData($mappingData);
            $allStatusData[$mappingData->getEntityType()] = $statusData;
        }

        $allExpectedData = [
            Product::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_RUNNING,
                StatusDataInterface::EXPORT_PERCENT => '50 %',
                StatusDataInterface::ACTION_LABEL => 'Cancel',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/cancel', [
                    'identifier' => 'products',
                ]),
            ],
            Customer::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_PROCESSED,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Rerun',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'customers',
                ]),
            ],
            Order::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'sales',
                ]),
            ],
            Subscriber::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_RUNNING,
                StatusDataInterface::EXPORT_PERCENT => '0 %',
                StatusDataInterface::ACTION_LABEL => 'Cancel',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/cancel', [
                    'identifier' => 'events',
                ]),
            ],
            Store::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'shops',
                ]),
            ],
        ];

        $this->assertStatusData($allExpectedData, $allStatusData);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testBuildByMappingDataNoInitial()
    {
        $allStatusData = [];
        $allMappingData = $this->mappingDataProvider->getAllMappingData();
        foreach ($allMappingData as $mappingData) {
            $statusData = $this->statusDataBuilder->buildByMappingData($mappingData);
            $allStatusData[$mappingData->getEntityType()] = $statusData;
        }

        $allExpectedData = [
            Product::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'products',
                ]),
            ],
            Customer::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'customers',
                ]),
            ],
            Order::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'sales',
                ]),
            ],
            Subscriber::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'events',
                ]),
            ],
            Store::class => [
                StatusDataInterface::STATUS_ID => Status::STATUS_IDLE,
                StatusDataInterface::EXPORT_PERCENT => '-',
                StatusDataInterface::ACTION_LABEL => 'Run',
                StatusDataInterface::ACTION_URL => $this->urlBuilder->getUrl('custobar/status/export', [
                    'identifier' => 'shops',
                ]),
            ],
        ];

        $this->assertStatusData($allExpectedData, $allStatusData);
    }

    /**
     * @param mixed[] $allExpectedData
     * @param StatusDataInterface[] $allStatusData
     *
     * @return void
     */
    private function assertStatusData(array $allExpectedData, array $allStatusData)
    {
        $this->assertEquals(\count($allExpectedData), \count($allStatusData));
        foreach ($allExpectedData as $entityType => $expectedData) {
            $statusData = $allStatusData[$entityType] ?? null;
            $this->assertNotNull($statusData);

            foreach ($expectedData as $field => $value) {
                $this->assertEquals(
                    $value,
                    $statusData->getData($field),
                    \sprintf(
                        'Assert that %s status data\'s %s matches expected value',
                        $entityType,
                        $field
                    )
                );
            }
        }
    }
}
