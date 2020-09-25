<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Custobar\CustoConnector\Model\Product\SkuProviderInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableResource;

class AddConfigurableData implements DataExtenderInterface
{
    /**
     * @var ConfigurableResource
     */
    private $configurableResource;

    /**
     * @var SkuProviderInterface
     */
    private $skuProvider;

    public function __construct(
        ConfigurableResource $configurableResource,
        SkuProviderInterface $skuProvider
    ) {
        $this->configurableResource = $configurableResource;
        $this->skuProvider = $skuProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Catalog\Model\Product $entity */

        if ($entity->getTypeId() == Configurable::TYPE_CODE) {
            $typeInstance = $entity->getTypeInstance();
            $childProducts = $typeInstance->getUsedProducts($entity);

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

        $parentIds = $this->configurableResource->getParentIdsByChild($entity->getId());
        $parentSkus = $this->skuProvider->getSkusByEntityIds(
            $entity->getStore()->getId(),
            $parentIds
        );
        if (!empty($parentSkus)) {
            $entity->setData('custobar_parent_ids', \implode(',', $parentSkus));
        }

        return $entity;
    }
}
