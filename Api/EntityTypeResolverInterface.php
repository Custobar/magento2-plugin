<?php

namespace Custobar\CustoConnector\Api;

interface EntityTypeResolverInterface
{
    /**
     * Returns entity type understood by this module based on the given entity
     *
     * @param mixed $entity
     * @return string
     */
    public function resolveEntityType($entity);
}
