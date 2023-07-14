<?php

namespace Custobar\CustoConnector\Model\CustobarApi\ClientBuilder;

use Custobar\CustoConnector\Model\CustobarApi\ClientInterface;

interface VersionClientProviderInterface
{
    /**
     * Get the client instance including any parameters given
     *
     * @param string $hostUrl
     * @param mixed[] $config
     *
     * @return ClientInterface
     */
    public function getClient(string $hostUrl, array $config);
}
