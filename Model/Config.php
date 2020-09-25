<?php

namespace Custobar\CustoConnector\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const CONFIG_BASE_ROOT = 'custobar/custobar_custoconnector/';
    const CONFIG_ALLOWED_WEBSITES = self::CONFIG_BASE_ROOT . 'allowed_websites';
    const CONFIG_API_PREFIX = self::CONFIG_BASE_ROOT . 'prefix';
    const CONFIG_API_KEY = self::CONFIG_BASE_ROOT . 'apikey';
    const CONFIG_MODE = self::CONFIG_BASE_ROOT . 'mode';
    const CONFIG_TRACKING_MODE = self::CONFIG_BASE_ROOT . 'tracking_mode';
    const CONFIG_TRACKING_SCRIPT = self::CONFIG_BASE_ROOT . 'tracking_script';

    const CONFIG_MAPPING_ROOT = 'custobar/custoconnector_field_mapping/';
    const CONFIG_MAPPING_PRODUCT = self::CONFIG_MAPPING_ROOT . 'product';
    const CONFIG_MAPPING_CUSTOMER = self::CONFIG_MAPPING_ROOT . 'customer';
    const CONFIG_MAPPING_ORDER = self::CONFIG_MAPPING_ROOT . 'order';
    const CONFIG_MAPPING_NEWSLETTER = self::CONFIG_MAPPING_ROOT . 'newsletter';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int[]
     */
    public function getAllowedWebsites()
    {
        $websites = $this->scopeConfig->getValue(
            self::CONFIG_ALLOWED_WEBSITES,
            ScopeInterface::SCOPE_STORE
        );

        return \explode(',', $websites);
    }

    /**
     * @return string
     */
    public function getApiPrefix()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_API_PREFIX,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_API_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isDevMode()
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getTrackingMode()
    {
        return (int)$this->scopeConfig->getValue(
            self::CONFIG_TRACKING_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getTrackingScript()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_TRACKING_SCRIPT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFieldMappingProduct()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_MAPPING_PRODUCT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFieldMappingCustomer()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_MAPPING_CUSTOMER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFieldMappingOrder()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_MAPPING_ORDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFieldMappingNewsletter()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_MAPPING_NEWSLETTER,
            ScopeInterface::SCOPE_STORE
        );
    }
}
