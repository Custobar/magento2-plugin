<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionProcessorInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Validation\ValidationException;

class ProductProvider implements ProductProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface[]
     */
    private $preProcessors;

    /**
     * @var CollectionProcessorInterface[]
     */
    private $postProcessors;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface[] $preProcessors
     * @param CollectionProcessorInterface[] $postProcessors
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        array $preProcessors = [],
        array $postProcessors = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->preProcessors = $preProcessors;
        $this->postProcessors = $postProcessors;
    }

    /**
     * @inheritDoc
     */
    public function getProduct(int $entityId, int $storeId)
    {
        $collection = $this->collectionFactory->create()
            ->setStoreId($storeId)
            ->addFieldToFilter('entity_id', $entityId)
            ->setPageSize(1);
        $collection = $this->processCollection($collection);

        return $collection->getItemById($entityId);
    }

    /**
     * @inheritDoc
     */
    public function getProducts(array $entityIds, int $storeId)
    {
        $collection = $this->collectionFactory->create()
            ->setStoreId($storeId)
            ->addFieldToFilter('entity_id', ['in' => $entityIds]);
        $collection = $this->processCollection($collection);

        return $collection->getItems();
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws ValidationException
     */
    private function processCollection($collection)
    {
        foreach ($this->preProcessors as $name => $collectionProcessor) {
            if ($collectionProcessor === null) {
                continue;
            }
            if (!($collectionProcessor instanceof CollectionProcessorInterface)) {
                throw new ValidationException(__(
                    'Product collection preprocessor \'%1\' is not valid',
                    $name
                ));
            }

            $collection = $collectionProcessor->execute($collection);
        }

        $collection->load();

        foreach ($this->postProcessors as $name => $collectionProcessor) {
            if ($collectionProcessor === null) {
                continue;
            }
            if (!($collectionProcessor instanceof CollectionProcessorInterface)) {
                throw new ValidationException(__(
                    'Product collection postprocessor \'%1\' is not valid',
                    $name
                ));
            }

            $collection = $collectionProcessor->execute($collection);
        }

        return $collection;
    }
}
