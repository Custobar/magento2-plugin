<?php

namespace Custobar\CustoConnector\Model\Product;

interface SkuProviderInterface
{
    /**
     * @param int $storeId
     * @param int[] $productIds
     * @return string[]
     */
    public function getSkusByEntityIds(int $storeId, array $productIds);
}
