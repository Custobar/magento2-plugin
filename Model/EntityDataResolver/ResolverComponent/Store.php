<?php

namespace Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponentInterface;
use Magento\Store\Model\Store as StoreEntity;
use Magento\Store\Model\ResourceModel\Store\CollectionFactory;

class Store implements ResolverComponentInterface
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
    public function execute(array $entityIds, int $storeId)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter(StoreEntity::STORE_ID, ['in' => $entityIds])
            ->addFieldToSelect('*');

        return $collection->getItems();
    }
}
