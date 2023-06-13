<?php

namespace Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProvider;

use Custobar\CustoConnector\Model\Config;
use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderInterface;
use Custobar\CustoConnector\Model\CustobarApi\Client\LaminasClientFactory;
use Magento\Framework\ObjectManagerInterface;

class LaminasClient implements VersionClientProviderInterface
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
     * @var LaminasClientFactory
     */
    private $clientFactory;

    /**
     * @param Config $config
     * @param ObjectManagerInterface $objectManager
     * @param LaminasClientFactory $clientFactory
     */
    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager,
        LaminasClientFactory $clientFactory
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
        $actualClient = $this->objectManager->create(\Magento\Framework\HTTP\LaminasClient::class, [
            'uri' => $hostUrl,
            'options' => $config,
        ]);
        $actualClient->setHeaders([
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'application/json',
            'Authorization' => 'Token ' . $this->config->getApiKey(),
        ]);
        $actualClient->setMethod('POST');

        // Instead of returning the actual client, return instance of
        // Custobar\CustoConnector\Model\CustobarApi\ClientInterface to make sure the calls to the client
        // don't need to care about the actual client underneath

        return $this->clientFactory->create(['client' => $actualClient]);
    }
}
