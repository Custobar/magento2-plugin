<?php
namespace Custobar\CustoConnector\Model\ResourceModel\LogData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(
            \Custobar\CustoConnector\Model\LogData::class,
            \Custobar\CustoConnector\Model\ResourceModel\LogData::class
        );
    }
}
