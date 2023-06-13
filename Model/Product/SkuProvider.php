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

        $collection = $this->collectionFactory->create()
            ->setStoreId($storeId)
            ->addFieldToSelect('sku')
            ->addFieldToFilter($metadata->getLinkField(), ['in' => $productIds])
            ->load();

        return $collection->getColumnValues('sku');
    }
}
