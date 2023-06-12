<?php

namespace Custobar\CustoConnector\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\MappingDataInterface;

interface StatusDataBuilderInterface
{
    /**
     * Create status data instance by mapping data instnace
     *
     * @param MappingDataInterface $mappingData
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function buildByMappingData(MappingDataInterface $mappingData);

    /**
     * Create status data instance by initial instance
     *
     * @param InitialInterface $initial
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function buildByInitial(InitialInterface $initial);
}
