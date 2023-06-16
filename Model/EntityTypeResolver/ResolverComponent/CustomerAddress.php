<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Address;

class CustomerAddress implements ResolverComponentInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($entity)
    {
        if ($entity instanceof AddressInterface) {
            return true;
        }
        if ($entity instanceof Address) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return Address::class;
    }
}
