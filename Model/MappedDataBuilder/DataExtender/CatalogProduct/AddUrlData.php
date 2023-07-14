<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;

class AddUrlData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Product $entity */

        if ($entity->isVisibleInSiteVisibility()) {
            $entity->setData('custobar_product_url', $this->resolveUrl($entity));
        }

        return $entity;
    }

    /**
     * Resolve url from product instance
     *
     * @param Product $product
     *
     * @return string
     * @throws NoSuchEntityException
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
