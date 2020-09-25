<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product;

interface ProductProviderInterface
{
    /**
     * @param int $entityId
     * @param int $storeId
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct(int $entityId, int $storeId);

    /**
     * @param int[] $entityIds
     * @param int $storeId
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProducts(array $entityIds, int $storeId);
}
