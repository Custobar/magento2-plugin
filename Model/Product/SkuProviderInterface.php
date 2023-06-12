<?php

namespace Custobar\CustoConnector\Model\Product;

interface SkuProviderInterface
{
    /**
     * Get skus by given parameters
     *
     * @param int $storeId
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getSkusByEntityIds(int $storeId, array $productIds);
}
