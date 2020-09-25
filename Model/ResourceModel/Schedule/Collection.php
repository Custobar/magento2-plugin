<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Schedule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(
            \Custobar\CustoConnector\Model\Schedule::class,
            \Custobar\CustoConnector\Model\ResourceModel\Schedule::class
        );
    }

    /**
     * Add filter by only ready for sending item
     *
     * @return \Custobar\CustoConnector\Model\ResourceModel\Schedule\Collection
     */
    public function addOnlyForSendingFilter()
    {
        $this->getSelect()->where('main_table.processed_at = "0000-00-00 00:00:00"');

        return $this;
    }

    /**
     * @return \Custobar\CustoConnector\Model\ResourceModel\Schedule\Collection
     */
    public function addOnlyErroneousFilter()
    {
        $this->addOnlyForSendingFilter();
        $this->getSelect()->where('main_table.errors > 0');

        return $this;
    }
}
