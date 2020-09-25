<?php

namespace Custobar\CustoConnector\Model\Initial\Entity\CollectionResolver;

use Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class SalesOrder implements CollectionResolverInterface
{
    /**
     * @var mixed
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
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }
}
