<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Validation;

use Custobar\CustoConnector\Model\Validation\WebsiteValidator;
use Magento\Catalog\Model\ProductRepository;
use \Magento\TestFramework\Helper\Bootstrap;

class WebsiteValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var WebsiteValidator
     */
    private $websiteValidator;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->get(ProductRepository::class);
        $this->websiteValidator = $this->objectManager->get(WebsiteValidator::class);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testIsWebsiteAllowedSuccess()
    {
        $result = $this->websiteValidator->isWebsiteAllowed(1);
        $this->assertTrue($result);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     */
    public function testIsWebsiteAllowedFail()
    {
        $result = $this->websiteValidator->isWebsiteAllowed(1);
        $this->assertFalse($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testIsProductInAllowedWebsitesSuccess()
    {
        $product = $this->productRepository->get('simple-249');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($product);
        $this->assertTrue($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     */
    public function testIsProductInAllowedWebsitesFailure()
    {
        $product = $this->productRepository->get('simple-249');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($product);
        $this->assertFalse($result);
    }
}
