<?php

namespace Custobar\CustoConnector\Model\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\EntityManager\MetadataPool;

class SkuProvider implements SkuProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param CollectionFactory $collectionFactory
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        MetadataPool $metadataPool
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->metadataPool = $metadataPool;
    }

    /**
     * @inheritDoc
     */
    public function getSkusByEntityIds(int $storeId, array $productIds)
    {
        $metadata = $this->metadataPool->getMetadata(ProductInterface::class);

        return $this->getSkus($metadata->getIdentifierField(), $storeId, $productIds);
    }

    /**
     * @inheritDoc
     */
    public function getSkusByLinkedIds(int $storeId, array $linkedIds)
    {
        $metadata = $this->metadataPool->getMetadata(ProductInterface::class);

        return $this->getSkus($metadata->getLinkField(), $storeId, $linkedIds);
    }

    /**
     * Get skus by given parameters
     *
     * @param string $linkField
     * @param int $storeId
     * @param int[] $productIds
     *
     * @return string[]
     */
    private function getSkus(string $linkField, int $storeId, array $productIds)
    {
        $collection = $this->collectionFactory->create()
            ->setStoreId($storeId)
            ->addFieldToSelect('sku')
            ->addFieldToFilter($linkField, ['in' => $productIds])
            ->load();

        return $collection->getColumnValues('sku');
    }
}
