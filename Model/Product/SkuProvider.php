<?php

namespace Custobar\CustoConnector\Model\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class SkuProvider implements SkuProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getSkusByEntityIds(int $storeId, array $productIds)
    {
        $collection = $this->collectionFactory->create()
            ->setStoreId($storeId)
            ->addFieldToSelect('sku')
            ->addFieldToFilter('entity_id', ['in' => $productIds])
            ->load();

        return $collection->getColumnValues('sku');
    }
}
