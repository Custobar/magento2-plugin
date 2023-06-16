<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;

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
        if ($entity instanceof Product) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return Product::class;
    }
}
