<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Custobar\CustoConnector\Model\Product\SkuProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\GroupedProduct\Model\ResourceModel\Product\Link;

class AddGroupedData implements DataExtenderInterface
{
    /**
     * @var Link
     */
    private $linkResource;

    /**
     * @var SkuProviderInterface
     */
    private $skuProvider;

    /**
     * @param Link $linkResource
     * @param SkuProviderInterface $skuProvider
     */
    public function __construct(
        Link $linkResource,
        SkuProviderInterface $skuProvider
    ) {
        $this->linkResource = $linkResource;
        $this->skuProvider = $skuProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Product $entity */

        if ($entity->getTypeId() == Grouped::TYPE_CODE) {
            $typeInstance = $entity->getTypeInstance();
            $childProducts = $typeInstance->getAssociatedProducts($entity);

            if ($childProducts) {
                $childSkus = [];
                foreach ($childProducts as $childProduct) {
                    $childSkus[] = $childProduct->getSku();
                }

                $entity->setData('custobar_child_ids', \implode(',', $childSkus));
            }

            return $entity;
        }
        if ($entity->getTypeId() != Type::TYPE_SIMPLE) {
            return $entity;
        }

        $parentIds = $this->linkResource->getParentIdsByChild(
            $entity->getId(),
            Link::LINK_TYPE_GROUPED
        );
        $parentSkus = $this->skuProvider->getSkusByLinkedIds(
            $entity->getStore()->getId(),
            $parentIds
        );
        if ($parentSkus) {
            $entity->setData('custobar_parent_ids', \implode(',', $parentSkus));
        }

        return $entity;
    }
}
