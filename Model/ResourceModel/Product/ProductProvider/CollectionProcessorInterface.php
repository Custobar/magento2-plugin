<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider;

interface CollectionProcessorInterface
{
    /**
     * Intended for modifying the given collection somehow, then returning it
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function execute($collection);
}
