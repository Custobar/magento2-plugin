<?php

namespace Custobar\CustoConnector\Test\Integration\Model;

use Custobar\CustoConnector\Model\EntityTypeResolver;
use Magento\Catalog\Model\Product;
use \Magento\TestFramework\Helper\Bootstrap;

class EntityTypeResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var EntityTypeResolver
     */
    private $typeResolver;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->typeResolver = $this->objectManager->get(EntityTypeResolver::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testResolveEntityType()
    {
        $expectedEntityType = Product::class;
        $product = $this->objectManager->create($expectedEntityType);
        $entityType = $this->typeResolver->resolveEntityType($product);

        $this->assertEquals($expectedEntityType, $entityType);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testResolveEntityTypeNone()
    {
        $entityType = $this->typeResolver->resolveEntityType(null);

        $this->assertEmpty($entityType);
    }
}
