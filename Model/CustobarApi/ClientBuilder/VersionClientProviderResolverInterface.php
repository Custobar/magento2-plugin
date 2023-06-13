<?php

namespace Custobar\CustoConnector\Model\CustobarApi\ClientBuilder;

interface VersionClientProviderResolverInterface
{
    /**
     * Check if getProvider() can be called with the given Magento version
     *
     * @param string $version
     *
     * @return bool
     */
    public function doesVersionApply(string $version);

    /**
     * Get the VersionClientProviderInterface instance
     *
     * @return VersionClientProviderInterface
     */
    public function getProvider();
}
