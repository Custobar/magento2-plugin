<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder;

interface DataExtenderInterface
{
    /**
     * Intended for modifying the given entity
     *
     * @param mixed $entity
     *
     * @return mixed
     */
    public function execute($entity);
}
