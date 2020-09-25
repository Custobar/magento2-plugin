<?php

namespace Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityDataResolver\ResolverComponentInterface;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

class NewsletterSubscriber implements ResolverComponentInterface
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
            ->addFieldToFilter('subscriber_id', ['in' => $entityIds])
            ->addFieldToSelect('*')
            ->addStoreFilter([$storeId]);

        return $collection->getItems();
    }
}
