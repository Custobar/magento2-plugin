<?php

namespace Custobar\CustoConnector\Test\Integration\Model;

use Custobar\CustoConnector\Model\MappingDataProvider;
use Magento\Catalog\Model\Product;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class MappingDataProviderTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var MappingDataProvider
     */
    private $dataProvider;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->dataProvider = $this->objectManager->get(MappingDataProvider::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testHasProductMappingData()
    {
        $entityType = Product::class;
        $mappingData = $this->dataProvider->getMappingDataByEntityType($entityType);
        $this->assertNotNull($mappingData);
    }
}
