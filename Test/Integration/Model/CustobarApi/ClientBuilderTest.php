<?php

namespace Custobar\CustoConnector\Test\Integration\Model\CustobarApi;

use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder;
use Custobar\CustoConnector\Model\CustobarApi\ClientUrlProvider;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class ClientBuilderTest extends TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var ClientUrlProvider
     */
    private $urlProvider;

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
        $this->clientBuilder = $this->objectManager->get(ClientBuilder::class);
    }

    /**
     * @magentoAppIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix domain
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/mode 1
     */
    public function testBuildClient()
    {
        $clientUrl = $this->urlProvider->getUploadUrl('test');
        $client = $this->clientBuilder->buildClient($clientUrl, []);

        $this->assertEquals(
            'https://dev.custobar.com/api/test/upload/',
            $client->getUri()->toString()
        );
    }
}
