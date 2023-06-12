<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

use Magento\Framework\HTTP\LaminasClient;

interface ClientBuilderInterface
{
    /**
     * Intended for constructing HTTP client
     *
     * @param string $hostUrl
     * @param mixed[] $config
     *
     * @return LaminasClient
     */
    public function buildClient(string $hostUrl, array $config);
}
