<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

interface ClientInterface
{
    // TODO: This can be removed once 2.4.6 becomes the oldest supported version for this module.
    // The only point of this is to make sure same method calls can be used for both LaminasClient
    // and ZendClient. See also ClientBuilder.php.

    /**
     * Send the actual request
     *
     * @param string $request
     *
     * @return void
     */
    public function sendRequest(string $request);

    /**
     * Get the response code from stored response
     *
     * @return string
     */
    public function getResponseCode();

    /**
     * Get the response body from stored response
     *
     * @return string
     */
    public function getResponseBody();

    /**
     * Get the actual client instance
     *
     * @return mixed
     */
    public function getRealClient();
}
