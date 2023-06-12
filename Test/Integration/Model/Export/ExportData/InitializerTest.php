<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Export\ExportData;

use Custobar\CustoConnector\Model\Export\ExportData\Initializer;
use Custobar\CustoConnector\Model\Schedule\ExportableProvider;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;

class InitializerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Initializer
     */
    private $initializer;

    /**
     * @var ExportableProvider
     */
    private $exportableProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->initializer = $this->objectManager->get(Initializer::class);
        $this->exportableProvider = $this->objectManager->get(ExportableProvider::class);
        $this->storeManager = $this->objectManager->get(StoreManagerInterface::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation disabled
     *
     * @magentoDataFixture Magento/Catalog/_files/product_simple_multistore.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_product_multistore.php
     * @magentoConfigFixture fixturestore_store custobar/custoconnector_field_mapping/product name:scope_title<br>sku:external_id<br>custobar_minimal_price:minimal_price<br>custobar_price:price<br>type_id:mage_type<br>configurable_min_price:my_configurable_min_price<br>custobar_attribute_set_name:type<br>custobar_category:category<br>custobar_category_id:category_id<br>custobar_image:image<br>custobar_product_url:url<br>custobar_special_price:sale_price<br>description:description<br>custobar_language:language<br>custobar_store_id:store_id<br>custobar_child_ids:mage_child_ids<br>custobar_parent_ids:mage_parent_ids
     */
    public function testInitializeBySchedules()
    {
        $expectedData = [
            Product::class => [
                [
                    'title' => 'Simple Product One',
                    'external_id' => 'simple',
                    'minimal_price' => 1000,
                    'price' => 1000,
                    'mage_type' => 'simple',
                    'type' => 'Default',
                    'category' => '',
                    'category_id' => '',
                    'sale_price' => 0,
                    'language' => 'en',
                    'store_id' => $this->storeManager->getStore('default')->getId(),
                ],
                [
                    'scope_title' => 'StoreTitle',
                    'external_id' => 'simple',
                    'minimal_price' => 1000,
                    'price' => 1000,
                    'mage_type' => 'simple',
                    'type' => 'Default',
                    'category' => '',
                    'category_id' => '',
                    'sale_price' => 0,
                    'language' => 'en',
                    'store_id' => $this->storeManager->getStore('fixturestore')->getId(),
                ],
            ],
        ];

        $schedules = $this->exportableProvider->getSchedulesForExport();
        $this->assertEquals(2, \count($schedules));
        $allSyncData = $this->initializer->initializeBySchedules($schedules);

        foreach ($expectedData as $entityType => $expectedItems) {
            $syncData = $allSyncData[$entityType] ?? null;
            $this->assertNotNull($syncData, \sprintf(
                'Assert that sync data for type \'%s\' is available',
                $entityType
            ));
            $dataRows = $syncData->getMappedDataRows();
            $this->assertEquals(\count($expectedItems), \count($dataRows), \sprintf(
                'Assert that type \'%s\' has expected number of mapped data rows',
                $entityType
            ));

            foreach ($expectedItems as $index => $expectedItem) {
                $dataRow = $dataRows[$index];
                $dataRow = \array_intersect_key($dataRow->getData(), $expectedItem);
                $this->assertEquals($expectedItem, $dataRow, \sprintf(
                    'Assert that mapped data row \'%s\' of \'%s\' has matching data',
                    $index,
                    $entityType
                ));
            }
        }
    }
}
