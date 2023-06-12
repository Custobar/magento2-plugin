<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Catalog\Model\Product;
use Magento\Directory\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AddLanguageData implements DataExtenderInterface
{
    public const DEFAULT_LANGUAGE = 'fi';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Product $entity */

        $storeId = (int)$entity->getStore()->getId();
        $lang = $this->resolveLanguage($storeId);
        $entity->setData('custobar_language', $lang);

        return $entity;
    }

    /**
     * Resolve locale code from store
     *
     * @param int $storeId
     *
     * @return string
     */
    private function resolveLanguage(int $storeId)
    {
        $locale = $this->scopeConfig->getValue(
            Data::XML_PATH_DEFAULT_LOCALE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $lang = self::DEFAULT_LANGUAGE;
        if ($locale) {
            $localeArray = \explode('_', $locale);
            $lang = $localeArray[0] ?? '';
        }

        return $lang;
    }
}
