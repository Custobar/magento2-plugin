<?php

namespace Custobar\CustoConnector\Block;

use Custobar\CustoConnector\Model\Config;
use Custobar\CustoConnector\Api\WebsiteValidatorInterface;
use Magento\Framework\View\Element\Template;

class Statistics extends Template
{
    /**
     * @var WebsiteValidatorInterface
     */
    private $websiteValidator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Template\Context $context
     * @param Config $config
     * @param WebsiteValidatorInterface $websiteValidator
     * @param mixed[] $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        WebsiteValidatorInterface $websiteValidator,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->websiteValidator = $websiteValidator;
    }

    /**
     * @return string
     */
    public function getTrackingScript()
    {
        return $this->config->getTrackingScript();
    }

    /**
     * @return int
     */
    public function getTrackingMode()
    {
        return $this->config->getTrackingMode();
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function canTrack()
    {
        $mode = $this->getTrackingMode();
        if (!$this->isAllowedWebsite() || empty($mode)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function isAllowedWebsite()
    {
        $store = $this->_storeManager->getStore();
        $websiteId = (int)$store->getWebsiteId();

        return $this->websiteValidator->isWebsiteAllowed($websiteId);
    }
}
