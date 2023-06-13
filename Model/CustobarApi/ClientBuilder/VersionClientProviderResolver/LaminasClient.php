<?php

namespace Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderResolver;

use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProvider\LaminasClientFactory;
use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderResolverInterface;

class LaminasClient implements VersionClientProviderResolverInterface
{
    /**
     * @var LaminasClientFactory
     */
    private $providerFactory;

    /**
     * @param LaminasClientFactory $providerFactory
     */
    public function __construct(
        LaminasClientFactory $providerFactory
    ) {
        $this->providerFactory = $providerFactory;
    }

    /**
     * @inheritDoc
     */
    public function doesVersionApply(string $version)
    {
        $version = (int)\str_replace('.', '', $version);

        return $version >= 246;
    }

    /**
     * @inheritDoc
     */
    public function getProvider()
    {
        return $this->providerFactory->create();
    }
}
