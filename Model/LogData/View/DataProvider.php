<?php

namespace Custobar\CustoConnector\Model\LogData\View;

use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Custobar\CustoConnector\Model\LogData\LocatorInterface;
use Custobar\CustoConnector\Model\ResourceModel\LogData\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var mixed[]
     */
    private $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param LocatorInterface $locator
     * @param mixed[] $meta
     * @param mixed[] $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        LocatorInterface $locator,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );

        $this->collectionFactory = $collectionFactory;
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter(
                LogDataInterface::LOG_ID,
                $this->locator->getCurrentLogId()
            );

            $this->collection = $collection;

        }

        return $this->collection;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        /** @var LogDataInterface[] $items */
        $items = $this->getCollection()->getItems();

        foreach ($items as $item) {
            $itemData = $item->toArray();

            // Using the php json methods for the JSON_PRETTY_PRINT flag
            $contextData = $item->getContextData();
            $contextData = \json_decode($contextData, true);
            $contextData = \json_encode($contextData, JSON_PRETTY_PRINT);
            $contextData = "<pre>{$contextData}</pre>";
            $itemData[LogDataInterface::CONTEXT_DATA] = $contextData;

            $this->loadedData[$item->getLogId()] = $itemData;
        }

        return $this->loadedData;
    }
}
