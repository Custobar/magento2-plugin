<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionPreProcessor;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionProcessorInterface;

class AddWebsiteData implements CollectionProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function execute($collection)
    {
        $collection->addWebsiteNamesToResult();

        return $collection;
    }
}
