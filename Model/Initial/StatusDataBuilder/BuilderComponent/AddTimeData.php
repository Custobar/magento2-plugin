<?php

namespace Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponent;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponentInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;

class AddTimeData implements BuilderComponentInterface
{
    /**
     * @inheritDoc
     */
    public function execute(StatusDataInterface $statusData, InitialInterface $initial)
    {
        $processedAt = $initial->getProcessedAt();
        if ($processedAt == '0000-00-00 00:00:00') {
            $processedAt = '';
        }
        $statusData->setLastExportTime($processedAt);

        return $statusData;
    }
}
