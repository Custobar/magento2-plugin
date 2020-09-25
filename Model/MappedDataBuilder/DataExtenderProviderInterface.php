<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder;

interface DataExtenderProviderInterface
{
    /**
     * Returns the hydrator for the given type
     *
     * @param string $entityType
     * @return \Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function getDataExtender(string $entityType);
}
