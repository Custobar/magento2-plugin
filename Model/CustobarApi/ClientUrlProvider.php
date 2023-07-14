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

    /**
     * @param Config $config
     */
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
        return \sprintf(
            'https://%s.custobar.com',
            $this->resolveDomain()
        );
    }

    /**
     * @inheritDoc
     */
    public function getUploadUrl(string $target)
    {
        return \sprintf(
            '%s/api/%s/upload/',
            $this->getBaseUrl(),
            $target
        );
    }

    /**
     * Get domain based on config
     *
     * @return string
     * @throws LocalizedException
     */
    private function resolveDomain()
    {
        $apiPrefix = $this->config->getApiPrefix();

        if ($this->config->isDevMode()) {
            $apiPrefix = 'dev';
        }

        if (!$apiPrefix) {
            throw new LocalizedException(__('Domain name must be set'));
        }

        return $apiPrefix;
    }
}
