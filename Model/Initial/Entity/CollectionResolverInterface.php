<?php

namespace Custobar\CustoConnector\Model\Initial\Entity;

interface CollectionResolverInterface
{
    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getCollection();
}
