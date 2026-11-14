<?php

namespace Custobar\CustoConnector\Test\Unit\Model\ResourceModel\Product\ProductProvider\CollectionPostProcessor;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionPostProcessor\AddInventoryData;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Inventory\Model\ResourceModel\SourceItem\CollectionFactory;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddInventoryDataTest extends TestCase
{
    /**
     * @var CollectionFactory|MockObject
     */
    private $collectionFactory;

    /**
     * @var StoreManagerInterface|MockObject
     */
    private $storeManager;

    /**
     * @var StockResolverInterface|MockObject
     */
    private $stockResolver;

    /**
     * @var ModuleManager|MockObject
     */
    private $moduleManager;

    /**
     * @var AddInventoryData
     */
    private $processor;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->stockResolver = $this->createMock(StockResolverInterface::class);
        $this->moduleManager = $this->createStub(ModuleManager::class);

        $this->processor = new AddInventoryData(
            $this->collectionFactory,
            $this->storeManager,
            $this->stockResolver,
            $this->moduleManager
        );
    }

    /**
     * @param string[] $enabledModules
     * @dataProvider disabledModulesProvider
     */
    #[DataProvider('disabledModulesProvider')]
    public function testExecuteSkipsInventoryLookupWhenMsiModulesAreDisabled(array $enabledModules)
    {
        $this->moduleManager->method('isEnabled')
            ->willReturnCallback(static function ($moduleName) use ($enabledModules) {
                return in_array($moduleName, $enabledModules, true);
            });
        $this->stockResolver->expects($this->never())->method('execute');
        $this->collectionFactory->expects($this->never())->method('create');
        $this->storeManager->expects($this->never())->method('getStore');

        $collection = $this->createStub(Collection::class);

        $this->assertSame($collection, $this->processor->execute($collection));
    }

    /**
     * @return array
     */
    public static function disabledModulesProvider()
    {
        return [
            'no inventory modules' => [[]],
            'inventory without sales' => [['Magento_Inventory']],
            'sales without inventory' => [['Magento_InventorySales']],
        ];
    }
}
