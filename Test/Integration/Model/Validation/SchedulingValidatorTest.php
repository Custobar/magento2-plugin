<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Validation;

use Custobar\CustoConnector\Api\SchedulingValidatorInterface;
use Magento\Catalog\Model\ResourceModel\Product;
use \Magento\TestFramework\Helper\Bootstrap;

class SchedulingValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var Product
     */
    private $productResource;

    /**
     * @var SchedulingValidatorInterface
     */
    private $schedulingValidator;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->productResource = $this->objectManager->get(Product::class);
        $this->schedulingValidator = $this->objectManager->get(SchedulingValidatorInterface::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixtureBeforeTransaction Magento/Catalog/_files/products_with_websites_and_stores.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testCanScheduleEntityTypeAndIdsWithProducts()
    {
        $productIds = [
            $this->productResource->getIdBySku('simple-1'),
            $this->productResource->getIdBySku('simple-2'),
        ];
        $expectedResults = [
            $this->productResource->getIdBySku('simple-1') => true,
            $this->productResource->getIdBySku('simple-2') => false,
        ];

        $results = $this->schedulingValidator->canScheduleEntityTypeAndIds(
            $productIds,
            \Magento\Catalog\Model\Product::class
        );
        $this->assertEquals($expectedResults, $results);
    }
}
