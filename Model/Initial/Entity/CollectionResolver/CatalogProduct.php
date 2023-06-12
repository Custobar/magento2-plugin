<?php

namespace Custobar\CustoConnector\Model\Initial\Entity\CollectionResolver;

use Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class CatalogProduct implements CollectionResolverInterface
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
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }
}
