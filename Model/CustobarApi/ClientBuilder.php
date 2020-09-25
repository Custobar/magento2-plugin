<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

use Custobar\CustoConnector\Model\Config;
use Magento\Framework\HTTP\ZendClientFactory;

class ClientBuilder implements ClientBuilderInterface
{
    /**
     * @var ZendClientFactory
     */
    private $clientFactory;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        ZendClientFactory $clientFactory,
        Config $config
    ) {
        $this->clientFactory = $clientFactory;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function buildClient(string $hostUrl, array $config)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->clientFactory->create();

        $client->setUri($hostUrl);
        $client->setConfig($config);
        $client->setHeaders('Content-Type', 'application/json');
        $client->setHeaders('Accept-Encoding', 'application/json');
        $client->setHeaders('Authorization', 'Token ' . $this->config->getApiKey());
        $client->setMethod(\Zend_Http_Client::POST);

        return $client;
    }
}
