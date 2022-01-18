<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator;

interface WebsiteResolverInterface
{
    /**
     * Returns the website ids related to the given entity ids
     *
     * @param string[] $entityIds
     *
     * @return int[][]
     */
    public function getEntityWebsiteIds(array $entityIds);
}
