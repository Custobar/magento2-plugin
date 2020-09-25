<?php

namespace Custobar\CustoConnector\Test\Integration\Model;

use Custobar\CustoConnector\Model\MappingDataProvider;
use \Magento\TestFramework\Helper\Bootstrap;

class MappingDataProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
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
    protected function setUp()
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
