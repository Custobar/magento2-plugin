<?php

namespace Custobar\CustoConnector\Test\Integration\Model\CustobarApi;

use Custobar\CustoConnector\Model\CustobarApi\ClientUrlProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class ClientUrlProviderTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ClientUrlProvider
     */
    private $urlProvider;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->urlProvider = $this->objectManager->get(ClientUrlProvider::class);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 1
     */
    public function testGetBaseUrlDevModeWithPrefix()
    {
        $expectedUrl = 'https://dev.custobar.com';
        $url = $this->urlProvider->getBaseUrl();
        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 0
     */
    public function testGetBaseUrlProductionWithPrefix()
    {
        $expectedUrl = 'https://domain.custobar.com';
        $url = $this->urlProvider->getBaseUrl();
        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 0
     */
    public function testGetBaseUrlProductionWithoutPrefix()
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Domain name must be set');
        $this->urlProvider->getBaseUrl();
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 1
     */
    public function testGetUploadUrlDevModeWithPrefix()
    {
        $expectedUrl = 'https://dev.custobar.com/api/target/upload/';
        $url = $this->urlProvider->getUploadUrl('target');
        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 0
     */
    public function testGetUploadUrlProductionWithPrefix()
    {
        $expectedUrl = 'https://domain.custobar.com/api/target/upload/';
        $url = $this->urlProvider->getUploadUrl('target');
        $this->assertEquals($expectedUrl, $url);
    }
}
