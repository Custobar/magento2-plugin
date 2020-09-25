<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;

class AddCategoryData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Catalog\Model\Product $entity */

        $categories = $entity->getCategoryCollection()
            ->setStoreId($entity->getStore()->getId())
            ->addAttributeToSelect('name');

        $categoriesNames = [];
        $categoriesIds = [];

        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($categories as $category) {
            $categoriesNames[] = $category->getName();
            $categoriesIds[] = $category->getId();
        }

        $entity->setData('custobar_category', \implode(',', $categoriesNames));
        $entity->setData('custobar_category_id', \implode(',', $categoriesIds));

        return $entity;
    }
}
