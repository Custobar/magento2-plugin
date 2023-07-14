<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder;

use Magento\Framework\Validation\ValidationException;

class DataExtenderChain implements DataExtenderInterface
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
    public function execute($entity)
    {
        foreach ($this->dataExtenders as $name => $dataExtender) {
            if ($dataExtender === null) {
                continue;
            }
            if (!($dataExtender instanceof DataExtenderInterface)) {
                throw new ValidationException(__('Data extender \'%1\' is not valid', $name));
            }

            $entity = $dataExtender->execute($entity);
        }

        return $entity;
    }
}
