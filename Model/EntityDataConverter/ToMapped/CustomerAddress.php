<?php

namespace Custobar\CustoConnector\Model\EntityDataConverter\ToMapped;

use Custobar\CustoConnector\Api\EntityDataConverterInterface;
use Magento\Customer\Model\Address;

class CustomerAddress implements EntityDataConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convertToMappedEntity($entity)
    {
        /** @var Address $entity */

        return $entity->getCustomer();
    }
}
