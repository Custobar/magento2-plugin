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

    /**
     * Get skus by linked product ids, useful if logic needs to cover row_id usage in Commerce
     *
     * @param int $storeId
     * @param int[] $linkedIds
     *
     * @return string[]
     */
    public function getSkusByLinkedIds(int $storeId, array $linkedIds);
}
