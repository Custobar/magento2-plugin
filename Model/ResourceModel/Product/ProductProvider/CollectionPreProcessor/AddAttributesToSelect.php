<?php

namespace Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionPreProcessor;

use Custobar\CustoConnector\Model\ResourceModel\Product\ProductProvider\CollectionProcessorInterface;
use Magento\Catalog\Model\Config;

class AddAttributesToSelect implements CollectionProcessorInterface
{
    /**
     * @var Config
     */
    private $catalogConfig;

    /**
     * @var string[]
     */
    private $additionalCodes;

    /**
     * @param Config $catalogConfig
     * @param string[] $additionalCodes
     */
    public function __construct(
        Config $catalogConfig,
        array $additionalCodes = []
    ) {
        $this->catalogConfig = $catalogConfig;
        $this->additionalCodes = $additionalCodes;
    }

    /**
     * @inheritDoc
     */
    public function execute($collection)
    {
        $productAttributes = $this->catalogConfig->getProductAttributes();
        if ($this->additionalCodes) {
            $productAttributes = \array_merge($productAttributes, $this->additionalCodes);
            $productAttributes = \array_values(\array_unique($productAttributes));
        }

        $collection->addAttributeToSelect($productAttributes);

        return $collection;
    }
}
