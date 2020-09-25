<?php

namespace Custobar\CustoConnector\Model\EntityDataResolver;

interface ResolverComponentInterface
{
    /**
     * Intended for converting the given ids to entity object
     *
     * @param string[] $entityIds
     * @param int $storeId
     * @return mixed
     */
    public function execute(array $entityIds, int $storeId);
}
