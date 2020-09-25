<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;

class AddBasicData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Catalog\Model\Product $entity */

        $entity->setData('custobar_price', \round((float)$entity->getPrice() * 100));
        $entity->setData('custobar_minimal_price', \round((float)$entity->getMinimalPrice() * 100));
        $entity->setData('custobar_special_price', \round((float)$entity->getSpecialPrice() * 100));
        $entity->setData('custobar_store_id', $entity->getStore()->getId());

        return $entity;
    }
}
