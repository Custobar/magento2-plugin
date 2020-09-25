<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddLanguageData implements DataExtenderInterface
{
    const DEFAULT_LANGUAGE = 'fi';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @inheritDoc
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
        /** @var \Magento\Catalog\Model\Product $entity */

        $storeId = (int)$entity->getStore()->getId();
        $lang = $this->resolveLanguage($storeId);
        $entity->setData('custobar_language', $lang);

        return $entity;
    }

    /**
     * @param int $storeId
     * @return string
     */
    private function resolveLanguage(int $storeId)
    {
        $locale = $this->scopeConfig->getValue(
            \Magento\Directory\Helper\Data::XML_PATH_DEFAULT_LOCALE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
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
