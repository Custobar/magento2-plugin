<?php

namespace Custobar\CustoConnector\Model\Initial\Entity\CollectionResolver;

use Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverInterface;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

class NewsletterSubscriber implements CollectionResolverInterface
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
