<?php

namespace Custobar\CustoConnector\Test\Integration\Block;

use Custobar\CustoConnector\Block\Statistics;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\SessionFactory;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractController;

class StatisticsTest extends AbstractController
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Statistics
     */
    private $statistics;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var SessionFactory
     */
    private $custSessionFactory;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        parent::setUp();

        $this->objectManager = Bootstrap::getObjectManager();
        $this->statistics = $this->objectManager->get(Statistics::class);
        $this->productRepository = $this->objectManager->get(ProductRepository::class);
        $this->custSessionFactory = $this->objectManager->get(SessionFactory::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script
     */
    public function testCustomScriptNoTrackingCode()
    {
        $this->assertEmpty($this->statistics->getTrackingScript());

        $this->dispatch('/');
        $html = $this->getResponse()->getBody();

        // Tracking script is intentionally shortened here since the long config line won't pass code quality checks
        $this->assertNotContains(
            'v1/custobar.js',
            $html,
            'Assert that code is not present as its set to empty'
        );
        $this->assertNotContains(
            'window.dataLayer.push(gtmData)',
            $html,
            'Assert that GTM script is not added instead'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture   Magento/Customer/_files/customer.php
     * @magentoDataFixture   Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script v1/custobar.js
     */
    public function testCustomScriptNotLoggedIn()
    {
        $this->dispatch('/');
        $html = $this->getResponse()->getBody();

        $this->assertContains(
            'v1/custobar.js',
            $html,
            'Assert that we get the code when its set'
        );
        $this->assertNotContains(
            'cstbrConfig.customerId =',
            $html,
            'Assert that no customer details is present'
        );
        $this->assertNotContains(
            'cstbrConfig.productId =',
            $html,
            'Assert that product line is not outputted'
        );
        $this->assertNotContains(
            'window.dataLayer.push(gtmData)',
            $html,
            'Assert that GTM script is not added instead'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture   Magento/Customer/_files/customer.php
     * @magentoDataFixture   Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 2
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script
     */
    public function testGtmNotLoggedIn()
    {
        $this->dispatch('/');
        $html = $this->getResponse()->getBody();

        $this->assertContains(
            'window.dataLayer.push(gtmData)',
            $html,
            'Assert that GTM script is added'
        );
        $this->assertNotContains(
            'v1/custobar.js',
            $html,
            'Assert that custom script is not added instead'
        );
        $this->assertNotContains(
            'cstbrConfig.customerId =',
            $html,
            'Assert that no customer details is present'
        );
        $this->assertNotContains(
            'cstbrConfig.productId =',
            $html,
            'Assert that product line is not outputted'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture   Magento/Customer/_files/customer.php
     * @magentoDataFixture   Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1,0
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script v1/custobar.js
     */
    public function testCustomScriptLoggedIn()
    {
        $this->custSessionFactory->create()->loginById(1);
        $product = $this->productRepository->get('simple_product_1');

        $this->dispatch(\sprintf(
            'catalog/product/view/id/%s',
            $product->getEntityId()
        ));

        $html = $this->getResponse()->getBody();

        $this->assertContains(
            'v1/custobar.js',
            $html,
            'Assert that custom script is added'
        );
        $this->assertNotContains(
            'window.dataLayer.push(gtmData)',
            $html,
            'Assert that GTM script is not added instead'
        );
        $this->assertContains(
            'cstbrConfig.customerId = "1";',
            $html,
            'Is customer id present'
        );
        $this->assertContains(
            'cstbrConfig.productId = "simple_product_1";',
            $html,
            'Is cb.track_browse_product present'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture   Magento/Customer/_files/customer.php
     * @magentoDataFixture   Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 2
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script
     */
    public function testGtmLoggedIn()
    {
        $this->custSessionFactory->create()->loginById(1);
        $product = $this->productRepository->get('simple_product_1');

        $this->dispatch(\sprintf(
            'catalog/product/view/id/%s',
            $product->getEntityId()
        ));

        $html = $this->getResponse()->getBody();

        $this->assertContains(
            'window.dataLayer.push(gtmData)',
            $html,
            'Assert that GTM script is added'
        );
        $this->assertNotContains(
            'v1/custobar.js',
            $html,
            'Assert that custom script is not added instead'
        );
        $this->assertContains(
            'cstbrConfig.customerId = "1";',
            $html,
            'Is customer id present'
        );
        $this->assertContains(
            'cstbrConfig.productId = "simple_product_1";',
            $html,
            'Is cb.track_browse_product present'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture   Magento/Customer/_files/customer.php
     * @magentoDataFixture   Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 1
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script v1/custobar.js
     */
    public function testCustomScriptWebsiteNotAllowed()
    {
        $this->custSessionFactory->create()->loginById(1);
        $product = $this->productRepository->get('simple_product_1');

        $this->dispatch(\sprintf(
            'catalog/product/view/id/%s',
            $product->getEntityId()
        ));

        $html = $this->getResponse()->getBody();

        $this->assertNotContains(
            'v1/custobar.js',
            $html,
            'Assert that custobar code is not present'
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture   Magento/Customer/_files/customer.php
     * @magentoDataFixture   Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_mode 2
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/tracking_script
     */
    public function testGtmWebsiteNotAllowed()
    {
        $this->custSessionFactory->create()->loginById(1);
        $product = $this->productRepository->get('simple_product_1');

        $this->dispatch(\sprintf(
            'catalog/product/view/id/%s',
            $product->getEntityId()
        ));

        $html = $this->getResponse()->getBody();

        $this->assertNotContains(
            'window.dataLayer.push(gtmData)',
            $html,
            'Assert that GTM script is not added'
        );
    }
}
