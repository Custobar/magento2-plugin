<?php

namespace Custobar\CustoConnector\Model\CustobarApi\Client;

use Custobar\CustoConnector\Model\CustobarApi\ClientInterface;

class LaminasClient implements ClientInterface
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
        $this->client->setRawBody($request);
        $this->response = $this->client->send();
    }

    /**
     * @inheritDoc
     */
    public function getResponseCode()
    {
        if (!$this->response) {
            return null;
        }

        return $this->response->getStatusCode();
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
