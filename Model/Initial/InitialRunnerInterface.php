<?php

namespace Custobar\CustoConnector\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\MappingDataInterface;

interface InitialRunnerInterface
{
    /**
     * Executes logic related to initial resolved from mapping data
     *
     * @param MappingDataInterface $mappingData
     *
     * @return InitialInterface
     */
    public function runInitialByMappingData(MappingDataInterface $mappingData);

    /**
     * Executes logic related to initial
     *
     * @param InitialInterface $initial
     *
     * @return InitialInterface
     */
    public function runInitial(InitialInterface $initial);
}
