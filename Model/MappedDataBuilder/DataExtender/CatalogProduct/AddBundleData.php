<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Custobar\CustoConnector\Model\Product\SkuProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Bundle\Model\Product\Type as Bundle;
use Magento\Bundle\Model\ResourceModel\Selection;

class AddBundleData implements DataExtenderInterface
{
    /**
     * @var Selection
     */
    private $selectionResource;

    /**
     * @var SkuProviderInterface
     */
    private $skuProvider;

    /**
     * @param Selection $selectionResource
     * @param SkuProviderInterface $skuProvider
     */
    public function __construct(
        Selection $selectionResource,
        SkuProviderInterface $skuProvider
    ) {
        $this->selectionResource = $selectionResource;
        $this->skuProvider = $skuProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Product $entity */

        if ($entity->getTypeId() == Bundle::TYPE_CODE) {
            $typeInstance = $entity->getTypeInstance();
            $childIds = $typeInstance->getChildrenIds($entity->getId(), false);
            $childSkus = $this->skuProvider->getSkusByEntityIds(
                $entity->getStore()->getId(),
                $childIds
            );

            if ($childSkus) {
                $entity->setData('custobar_child_ids', \implode(',', $childSkus));
            }

            return $entity;
        }
        if ($entity->getTypeId() != Type::TYPE_SIMPLE) {
            return $entity;
        }

        $parentIds = $this->selectionResource->getParentIdsByChild($entity->getId());

        // bundle item appears as a parent also for some reason. Pop at least that out
        $index = \array_search($entity->getId(), $parentIds);
        if ($index !== false) {
            unset($parentIds[$index]);
        }

        $parentSkus = $this->skuProvider->getSkusByEntityIds(
            $entity->getStore()->getId(),
            $parentIds
        );
        if ($parentSkus) {
            $entity->setData('custobar_parent_ids', \implode(',', $parentSkus));
        }

        return $entity;
    }
}
