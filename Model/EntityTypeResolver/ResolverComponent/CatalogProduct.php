<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class CatalogProduct implements ResolverComponentInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($entity)
    {
        if ($entity instanceof ProductInterface) {
            return true;
        }
        if ($entity instanceof \Magento\Catalog\Model\Product) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return \Magento\Catalog\Model\Product::ENTITY;
    }
}
