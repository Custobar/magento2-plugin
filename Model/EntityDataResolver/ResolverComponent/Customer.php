<?php

namespace Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponentInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class Customer implements ResolverComponentInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

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
            ->addFieldToFilter('entity_id', ['in' => $entityIds])
            ->addFieldToFilter('store_id', $storeId)
            ->addAttributeToSelect('*')
            ->addFieldToSelect('*');

        return $collection->getItems();
    }
}
