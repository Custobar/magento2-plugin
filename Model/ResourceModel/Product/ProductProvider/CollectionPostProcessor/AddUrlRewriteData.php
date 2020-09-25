<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionPostProcessor;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionProcessorInterface;

class AddUrlRewriteData implements CollectionProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function execute($collection)
    {
        $collection->addUrlRewrite();

        return $collection;
    }
}
