<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

use Custobar\CustoConnector\Model\Config;
use Laminas\Http\Request as HttpRequest;
use Magento\Framework\HTTP\LaminasClient;
use Magento\Framework\HTTP\LaminasClientFactory;

class ClientBuilder implements ClientBuilderInterface
{
    /**
     * @var LaminasClientFactory
     */
    private $clientFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param LaminasClientFactory $clientFactory
     * @param Config $config
     */
    public function __construct(
        LaminasClientFactory $clientFactory,
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
        /** @var LaminasClient $client */
        $client = $this->clientFactory->create(['uri' => $hostUrl, 'options' => $config]);
        $client->setHeaders([
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'application/json',
            'Authorization' => 'Token ' . $this->config->getApiKey(),
        ]);
        $client->setMethod(HttpRequest::METHOD_POST);

        return $client;
    }
}
