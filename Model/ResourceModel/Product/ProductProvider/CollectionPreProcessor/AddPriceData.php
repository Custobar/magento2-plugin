<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionPreProcessor;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionProcessorInterface;
use Magento\Framework\DB\Select;

class AddPriceData implements CollectionProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function execute($collection)
    {
        $collection->addPriceData();

        // Change the price inner join to a left join so we get products that are out of stock
        $select = $collection->getSelect();
        $fromAndJoins = $select->getPart(Select::FROM);
        foreach ($fromAndJoins as $item => $value) {
            if ($item == 'price_index') {
                $value['joinType'] = Select::LEFT_JOIN;
                $fromAndJoins[$item] = $value;
            }
        }
        $select->setPart(Select::FROM, $fromAndJoins);

        return $collection;
    }
}
