<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Customer\Api\Data\CustomerInterface;

class Customer implements ResolverComponentInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($entity)
    {
        if ($entity instanceof CustomerInterface) {
            return true;
        }
        if ($entity instanceof \Magento\Customer\Model\Customer) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return \Magento\Customer\Model\Customer::class;
    }
}
