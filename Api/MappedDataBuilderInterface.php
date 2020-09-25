<?php

namespace Custobar\CustoConnector\Api;

interface MappedDataBuilderInterface
{
    /**
     * Returns Custobar API mapped data based on the given entity
     *
     * @param mixed $entity
     * @return \Magento\Framework\DataObject
     */
    public function buildMappedData($entity);
}
