<?php

namespace Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderResolver;

use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProvider\ZendClientFactory;
use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderResolverInterface;

class ZendClient implements VersionClientProviderResolverInterface
{
    /**
     * @var ZendClientFactory
     */
    private $providerFactory;

    /**
     * @param ZendClientFactory $providerFactory
     */
    public function __construct(
        ZendClientFactory $providerFactory
    ) {
        $this->providerFactory = $providerFactory;
    }

    /**
     * @inheritDoc
     */
    public function doesVersionApply(string $version)
    {
        $version = (int)\str_replace('.', '', $version);

        return $version < 246;
    }

    /**
     * @inheritDoc
     */
    public function getProvider()
    {
        return $this->providerFactory->create();
    }
}
