<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Framework\UrlInterface;

class AddImageData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Catalog\Model\Product $entity */

        $store = $entity->getStore();
        $mediaBaseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $imageLink = "{$mediaBaseUrl}catalog/product{$entity->getImage()}";
        $entity->setData('custobar_image', $imageLink);

        return $entity;
    }
}
