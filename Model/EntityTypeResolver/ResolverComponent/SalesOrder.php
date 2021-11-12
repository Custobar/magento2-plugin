<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Sales\Api\Data\OrderInterface;

class SalesOrder implements ResolverComponentInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($entity)
    {
        if ($entity instanceof OrderInterface) {
            return true;
        }
        if ($entity instanceof \Magento\Sales\Model\Order) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return \Magento\Sales\Model\Order::class;
    }
}
