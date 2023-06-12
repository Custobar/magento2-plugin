<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;

class AddCategoryData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Product $entity */

        $categories = $entity->getCategoryCollection()
            ->setStoreId($entity->getStore()->getId())
            ->addAttributeToSelect('name');

        $categoriesNames = [];
        $categoriesIds = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $categoriesNames[] = $category->getName();
            $categoriesIds[] = $category->getId();
        }

        $entity->setData('custobar_category', \implode(',', $categoriesNames));
        $entity->setData('custobar_category_id', \implode(',', $categoriesIds));

        return $entity;
    }
}
