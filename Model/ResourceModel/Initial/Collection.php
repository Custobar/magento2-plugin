<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Initial;

use Custobar\CustoConnector\Model\Initial as Model;
use Custobar\CustoConnector\Model\ResourceModel\Initial as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _construct()
    {
        $this->_init(
            Model::class,
            ResourceModel::class
        );
    }
}
