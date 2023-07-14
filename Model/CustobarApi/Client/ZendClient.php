<?php

namespace Custobar\CustoConnector\Model\CustobarApi\Client;

use Custobar\CustoConnector\Model\CustobarApi\ClientInterface;

class ZendClient implements ClientInterface
{
    /**
     * @var mixed
     */
    private $client;

    /**
     * @var mixed
     */
    private $response;

    /**
     * @param mixed $client
     */
    public function __construct(
        $client
    ) {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(string $request)
    {
        $this->client->setRawData($request);
        $this->response = $this->client->request();
    }

    /**
     * @inheritDoc
     */
    public function getResponseCode()
    {
        if (!$this->response) {
            return null;
        }

        return $this->response->getStatus();
    }

    /**
     * @inheritDoc
     */
    public function getResponseBody()
    {
        if (!$this->response) {
            return null;
        }

        return \trim($this->response->getBody());
    }

    /**
     * @inheritDoc
     */
    public function getRealClient()
    {
        return $this->client;
    }
}
