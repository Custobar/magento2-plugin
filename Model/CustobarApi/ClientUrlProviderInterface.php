<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

interface ClientUrlProviderInterface
{
    /**
     * Returns the base url for all Custobar APIs
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Returns the data upload url for the given target
     *
     * @param string $target
     *
     * @return string
     */
    public function getUploadUrl(string $target);
}
