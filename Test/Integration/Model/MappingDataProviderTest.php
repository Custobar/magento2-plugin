<?php

namespace Custobar\CustoConnector\Test\Integration\Model;

use Custobar\CustoConnector\Model\MappingDataProvider;
use \Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;

class MappingDataProviderTest extends \PHPUnit\Framework\TestCase
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
        $entityType = \Magento\Catalog\Model\Product::class;
        $mappingData = $this->dataProvider->getMappingDataByEntityType($entityType);
        $this->assertNotNull($mappingData);
    }
}
