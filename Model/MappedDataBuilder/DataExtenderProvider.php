<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Validation\ValidationException;

class DataExtenderProvider implements DataExtenderProviderInterface
{
    /**
     * @var DataExtenderInterface[]
     */
    private $dataExtenders;

    /**
     * @param DataExtenderInterface[] $dataExtenders
     */
    public function __construct(
        array $dataExtenders = []
    ) {
        $this->dataExtenders = $dataExtenders;
    }

    /**
     * @inheritDoc
     */
    public function getDataExtender(string $entityType)
    {
        $dataExtender = $this->dataExtenders[$entityType] ?? null;
        if ($dataExtender === null) {
            throw new NotFoundException(\__('No data extender found for \'%1\'', $entityType));
        }
        if (!($dataExtender instanceof DataExtenderInterface)) {
            throw new ValidationException(\__('Data extender found for \'%1\' is not valid', $entityType));
        }

        return $dataExtender;
    }
}
