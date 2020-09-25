<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Initial;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(
            \Custobar\CustoConnector\Model\Initial::class,
            \Custobar\CustoConnector\Model\ResourceModel\Initial::class
        );
    }
}
