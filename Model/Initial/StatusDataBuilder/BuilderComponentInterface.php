<?php

namespace Custobar\CustoConnector\Model\Initial\StatusDataBuilder;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;

interface BuilderComponentInterface
{
    /**
     * @param StatusDataInterface $statusData
     * @param InitialInterface $initial
     * @return StatusDataInterface
     */
    public function execute(StatusDataInterface $statusData, InitialInterface $initial);
}
