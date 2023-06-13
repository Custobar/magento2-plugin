<?php

namespace Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProvider;

use Custobar\CustoConnector\Model\Config;
use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderInterface;
use Custobar\CustoConnector\Model\CustobarApi\Client\ZendClientFactory;
use Magento\Framework\ObjectManagerInterface;

class ZendClient implements VersionClientProviderInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ZendClientFactory
     */
    private $clientFactory;

    /**
     * @param Config $config
     * @param ObjectManagerInterface $objectManager
     * @param ZendClientFactory $clientFactory
     */
    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager,
        ZendClientFactory $clientFactory
    ) {
        $this->config = $config;
        $this->objectManager = $objectManager;
        $this->clientFactory = $clientFactory;
    }

    /**
     * @inheritDoc
     */
    public function getClient(string $hostUrl, array $config)
    {
        // Must use ObjectManager to avoid dependency issues with versions where this doesn't run
        $actualClient = $this->objectManager->create(\Magento\Framework\HTTP\ZendClient::class);

        $actualClient->setUri($hostUrl);
        $actualClient->setConfig($config);
        $actualClient->setHeaders('Content-Type', 'application/json');
        $actualClient->setHeaders('Accept-Encoding', 'application/json');
        $actualClient->setHeaders('Authorization', 'Token ' . $this->config->getApiKey());
        $actualClient->setMethod('POST');

        // Instead of returning the actual client, return instance of
        // Custobar\CustoConnector\Model\CustobarApi\ClientInterface to make sure the calls to the client
        // don't need to care about the actual client underneath

        return $this->clientFactory->create(['client' => $actualClient]);
    }
}
