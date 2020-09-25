<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionPostProcessor;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionProcessorInterface;

class AddCategoryData implements CollectionProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function execute($collection)
    {
        $collection->addCategoryIds();

        return $collection;
    }
}
