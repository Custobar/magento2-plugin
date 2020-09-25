<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Catalog\Model\Product;

class AddUrlData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Catalog\Model\Product $entity */

        if ($entity->isVisibleInSiteVisibility()) {
            $entity->setData('custobar_product_url', $this->resolveUrl($entity));
        }

        return $entity;
    }

    /**
     * @param Product $product
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function resolveUrl(Product $product)
    {
        $url = $product->getProductUrl();
        if ($url) {
            return $product->getStore()->getUrl($url);
        }

        $storeId = $product->getStore()->getId();

        return $product->getUrlInStore(
            ["_store" => $storeId]
        );
    }
}
