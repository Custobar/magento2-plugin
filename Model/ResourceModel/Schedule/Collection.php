<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Schedule;

use Custobar\CustoConnector\Model\ResourceModel\Schedule as ResourceModel;
use Custobar\CustoConnector\Model\Schedule as Model;
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

    /**
     * Add filter to return only rows that are ready for sending
     *
     * @return Collection
     */
    public function addOnlyForSendingFilter()
    {
        $this->getSelect()->where('main_table.processed_at = "0000-00-00 00:00:00"');

        return $this;
    }

    /**
     * Add filter to return only rows that have failed to export
     *
     * @return Collection
     */
    public function addOnlyErroneousFilter()
    {
        $this->addOnlyForSendingFilter();
        $this->getSelect()->where('main_table.errors > 0');

        return $this;
    }
}
