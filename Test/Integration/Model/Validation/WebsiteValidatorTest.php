<?php

namespace Custobar\CustoConnector\Test\Integration\Model\Validation;

use Custobar\CustoConnector\Model\Validation\WebsiteValidator;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WebsiteValidatorTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var WebsiteValidator
     */
    private $websiteValidator;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->get(ProductRepository::class);
        $this->customerRepository = $this->objectManager->get(CustomerRepositoryInterface::class);
        $this->orderFactory = $this->objectManager->get(OrderFactory::class);
        $this->subscriberFactory = $this->objectManager->get(SubscriberFactory::class);
        $this->storeManager = $this->objectManager->get(StoreManagerInterface::class);
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

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testIsCustomerInAllowedWebsitesSuccess()
    {
        $customer = $this->customerRepository->get('customer@example.com');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($customer);
        $this->assertTrue($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     */
    public function testIsCustomerInAllowedWebsitesFailure()
    {
        $customer = $this->customerRepository->get('customer@example.com');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($customer);
        $this->assertFalse($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testIsOrderInAllowedWebsitesSuccess()
    {
        $order = $this->orderFactory->create()->loadByIncrementId('100000001');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($order);
        $this->assertTrue($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     */
    public function testIsOrderInAllowedWebsitesFailure()
    {
        $order = $this->orderFactory->create()->loadByIncrementId('100000001');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($order);
        $this->assertFalse($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testIsNewsletterSubscriberInAllowedWebsitesSuccess()
    {
        $subscriber = $this->subscriberFactory->create()->loadByEmail('customer@example.com');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($subscriber);
        $this->assertTrue($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     */
    public function testIsNewsletterSubscriberInAllowedWebsitesFailure()
    {
        $subscriber = $this->subscriberFactory->create()->loadByEmail('customer@example.com');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($subscriber);
        $this->assertFalse($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     */
    public function testIsStoreInAllowedWebsitesSuccess()
    {
        $store = $this->storeManager->getStore('default');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($store);
        $this->assertTrue($result);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     */
    public function testIsStoreInAllowedWebsitesFailure()
    {
        $store = $this->storeManager->getStore('default');
        $result = $this->websiteValidator->isEntityInAllowedWebsites($store);
        $this->assertFalse($result);
    }
}
