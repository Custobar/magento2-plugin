<?php

namespace Custobar\CustoConnector\Model\Initial\Entity;

interface CollectionResolverInterface
{
    /**
     * Get entity collection instance
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getCollection();
}
