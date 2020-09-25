<?php

namespace Custobar\CustoConnector\Model\LogData\Listing;

use Custobar\CustoConnector\Model\ResourceModel\LogData\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param mixed[] $meta
     * @param mixed[] $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
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
    }

    /**
     * @return \Custobar\CustoConnector\Model\ResourceModel\LogData\Collection
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create();
        }

        return $this->collection;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        $this->getCollection()->addFieldToFilter(
            $filter->getField(),
            [$filter->getConditionType() => $filter->getValue()]
        );
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $arrItems = [];
        $arrItems['totalRecords'] = $this->getCollection()->getSize();
        $arrItems['items'] = [];
        $arrItems['allIds'] = $this->getCollection()->getAllIds();

        $items = $this->getCollection()->getItems();
        foreach ($items as $item) {
            $arrItems['items'][] = $item->toArray();
        }

        return $arrItems;
    }
}
