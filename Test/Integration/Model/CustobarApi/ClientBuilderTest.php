<?php

namespace Custobar\CustoConnector\Test\Integration\Model\CustobarApi;

use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder;
use Custobar\CustoConnector\Model\CustobarApi\ClientUrlProvider;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class ClientBuilderTest extends TestCase
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
     * @var ProductMetadataInterface
     */
    private $metadata;

    /**
     * @var ClientBuilder
     */
    private $clientBuilder;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->urlProvider = $this->objectManager->get(ClientUrlProvider::class);
        $this->metadata = $this->objectManager->get(ProductMetadataInterface::class);
        $this->clientBuilder = $this->objectManager->create(ClientBuilder::class);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 1
     */
    public function testBuildClientOn245AndOlder()
    {
        if ($this->getCurrentSystemVersionAsInteger() >= 246) {
            $this->markTestSkipped('Test unsupported in 2.4.6 and newer');
        }

        $clientUrl = $this->urlProvider->getUploadUrl('test');
        $client = $this->clientBuilder->buildClient($clientUrl, []);
        $realClient = $client->getRealClient();

        $this->assertEquals(
            'https://dev.custobar.com:443/api/test/upload/',
            $realClient->getUri(true)
        );
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 1
     */
    public function testBuildClientOn246AndNewer()
    {
        if ($this->getCurrentSystemVersionAsInteger() < 246) {
            $this->markTestSkipped('Test unsupported in 2.4.5 and older');
        }

        $clientUrl = $this->urlProvider->getUploadUrl('test');
        $client = $this->clientBuilder->buildClient($clientUrl, []);
        $realClient = $client->getRealClient();

        $this->assertEquals(
            'https://dev.custobar.com/api/test/upload/',
            $realClient->getUri()->toString()
        );
    }

    /**
     * @return int
     */
    private function getCurrentSystemVersionAsInteger()
    {
        $version = $this->metadata->getVersion();

        return (int) \str_replace('.', '', $version);
    }
}
