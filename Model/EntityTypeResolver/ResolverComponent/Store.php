<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Store\Api\Data\StoreInterface;

class Store implements ResolverComponentInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($entity)
    {
        if ($entity instanceof StoreInterface) {
            return true;
        }
        if ($entity instanceof \Magento\Store\Model\Store) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return \Magento\Store\Model\Store::ENTITY;
    }
}
