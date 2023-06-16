<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product;

interface ProductProviderInterface
{
    /**
     * Get product by given parameters
     *
     * @param int $entityId
     * @param int $storeId
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct(int $entityId, int $storeId);

    /**
     * Get multiple products by given parameters
     *
     * @param int[] $entityIds
     * @param int $storeId
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProducts(array $entityIds, int $storeId);
}
