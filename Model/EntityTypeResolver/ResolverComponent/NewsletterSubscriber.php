<?php

namespace Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponent;

use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Newsletter\Model\Subscriber;

class NewsletterSubscriber implements ResolverComponentInterface
{
    /**
     * @inheritDoc
     */
    public function isMatch($entity)
    {
        if ($entity instanceof Subscriber) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return Subscriber::class;
    }
}
