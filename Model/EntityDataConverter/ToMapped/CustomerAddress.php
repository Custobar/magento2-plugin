<?php

namespace Custobar\CustoConnector\Model\EntityDataConverter\ToMapped;

use Custobar\CustoConnector\Api\EntityDataConverterInterface;

class CustomerAddress implements EntityDataConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convertToMappedEntity($entity)
    {
        /** @var \Magento\Customer\Model\Address $entity */

        return $entity->getCustomer();
    }
}
