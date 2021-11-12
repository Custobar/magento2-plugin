<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\CatalogProduct;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Exception\LocalizedException;

class AddAttributeData implements DataExtenderInterface
{
    /**
     * @var Product
     */
    private $productResource;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var SetFactory
     */
    private $attributeSetFactory;

    public function __construct(
        Product $productResource,
        MappingDataProviderInterface $mappingDataProvider,
        SetFactory $attributeSetFactory
    ) {
        $this->productResource = $productResource;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Catalog\Model\Product $entity */

        $attributeSetModel = $this->attributeSetFactory->create();
        $attributeSetModel->load($entity->getAttributeSetId());
        $attributeSetName = $attributeSetModel->getAttributeSetName();
        $entity->setData('custobar_attribute_set_name', $attributeSetName);

        $attributes = $entity->getAttributes();
        $mappingData = $this->mappingDataProvider->getMappingDataByEntityType(\Magento\Catalog\Model\Product::ENTITY);
        $fieldMap = $mappingData->getFieldMap();

        $additionalData = [];
        foreach ($fieldMap as $index => $item) {
            if (\strpos($index, 'custobar') !== false || !\array_key_exists($index, $attributes)) {
                continue;
            }

            $productAttribute = $attributes[$index];
            $input = $productAttribute->getFrontend()->getConfigField('input');
            $rawValue = $this->productResource->getAttributeRawValue(
                $entity->getId(),
                $index,
                $entity->getStore()->getId()
            );
            $rawValue = \is_array($rawValue) ? $rawValue[$index] ?? null : $rawValue;
            if (!$rawValue) {
                continue;
            }

            if (!\in_array($input, ['select', 'multiselect'])) {
                $additionalData[$index] = $rawValue;

                continue;
            }

            $entity->setData($index, $rawValue);
            $additionalData[$index] = $productAttribute->getFrontend()->getValue($entity);
        }

        $entity->addData($additionalData);

        return $entity;
    }
}
