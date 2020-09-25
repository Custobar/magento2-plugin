<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

use Custobar\CustoConnector\Model\Config;
use Magento\Framework\Exception\LocalizedException;

class ClientUrlProvider implements ClientUrlProviderInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl()
    {
        return "https://{$this->resolveDomain()}.custobar.com";
    }

    /**
     * @inheritDoc
     */
    public function getUploadUrl(string $target)
    {
        return "{$this->getBaseUrl()}/api/{$target}/upload/";
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    private function resolveDomain()
    {
        $apiPrefix = $this->config->getApiPrefix();

        if ($this->config->isDevMode()) {
            $apiPrefix = 'dev';
        }

        if (empty($apiPrefix)) {
            throw new LocalizedException(\__('Domain name must be set'));
        }

        return $apiPrefix;
    }
}
