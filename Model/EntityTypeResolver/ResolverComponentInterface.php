<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver;

interface ResolverComponentInterface
{
    /**
     * Checks if the given object is a match to this resolver
     *
     * @param mixed $entity
     * @return bool
     */
    public function isMatch($entity);

    /**
     * Intended to be called after isMatch passes, returns the type for entity
     *
     * @return string
     */
    public function getEntityType();
}
