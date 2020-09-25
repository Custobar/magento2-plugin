<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

interface ClientBuilderInterface
{
    /**
     * Intended for constructing HTTP client
     *
     * @param string $hostUrl
     * @param mixed[] $config
     * @return \Magento\Framework\HTTP\ZendClient
     */
    public function buildClient(string $hostUrl, array $config);
}
