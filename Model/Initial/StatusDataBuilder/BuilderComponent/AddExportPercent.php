<?php

namespace Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponent;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status as InitialStatus;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponentInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;

class AddExportPercent implements BuilderComponentInterface
{
    /**
     * @inheritDoc
     */
    public function execute(StatusDataInterface $statusData, InitialInterface $initial)
    {
        if (!$initial) {
            $statusData->setExportPercent('-');

            return $statusData;
        }
        if (!$initial->getPages() || $initial->getStatus() != InitialStatus::STATUS_RUNNING) {
            $statusData->setExportPercent('-');

            return $statusData;
        }

        $percent = (int)(($initial->getPage() / $initial->getPages()) * 100);
        $statusData->setExportPercent("{$percent} %");

        return $statusData;
    }
}
