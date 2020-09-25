<?php

namespace Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponentInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class SalesOrder implements ResolverComponentInterface
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
            ->addFieldToFilter(Order::ENTITY_ID, ['in' => $entityIds])
            ->addFieldToFilter(Order::STORE_ID, $storeId)
            ->addFieldToSelect('*');

        return $collection->getItems();
    }
}
