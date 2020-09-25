<?php

namespace Custobar\CustoConnector\Model\MappingDataProvider;

use Custobar\CustoConnector\Api\Data\MappingDataInterface;

interface DataExtenderInterface
{
    /**
     * Intended for modifying the given mapping data and returning the modified one back
     *
     * @param MappingDataInterface $mappingData
     *
     * @return MappingDataInterface
     */
    public function extendData(MappingDataInterface $mappingData);
}
