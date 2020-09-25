<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator;

interface EntityWebsiteResolverInterface
{
    /**
     * Returns the website ids related to the given entity
     *
     * @param mixed $entity
     * @return int[]
     */
    public function getEntityWebsiteIds($entity);
}
