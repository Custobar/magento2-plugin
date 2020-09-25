<?php

namespace Custobar\CustoConnector\Api;

interface EntityDataConverterInterface
{
    /**
     * If given entity is related to mapped entity but not directly, this returns the mapped entity
     *
     * @param mixed $entity
     * @return mixed
     */
    public function convertToMappedEntity($entity);
}
