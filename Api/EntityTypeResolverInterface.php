<?php

namespace Custobar\CustoConnector\Api;

interface EntityTypeResolverInterface
{
    /**
     * Returns type for the given entity
     *
     * @param mixed $entity
     * @return string
     */
    public function resolveEntityType($entity);
}
