<?php

namespace Custobar\CustoConnector\Model\ResourceModel\LogData;

use Custobar\CustoConnector\Model\LogData as Model;
use Custobar\CustoConnector\Model\ResourceModel\LogData as ResourceModel;
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
