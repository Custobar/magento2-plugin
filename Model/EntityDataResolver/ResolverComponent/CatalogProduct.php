<?php

namespace Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponent;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProviderInterface;
use Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponentInterface;

class CatalogProduct implements ResolverComponentInterface
{
    /**
     * @var ProductProviderInterface
     */
    private $productProvider;

    public function __construct(
        ProductProviderInterface $productProvider
    ) {
        $this->productProvider = $productProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $entityIds, int $storeId)
    {
        return $this->productProvider->getProducts($entityIds, $storeId);
    }
}
