<?php

namespace Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponent;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponentInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;

class AddStatusLabel implements BuilderComponentInterface
{
    /**
     * @var Status
     */
    private $statusSource;

    /**
     * @param Status $statusSource
     */
    public function __construct(
        Status $statusSource
    ) {
        $this->statusSource = $statusSource;
    }

    /**
     * @inheritDoc
     */
    public function execute(StatusDataInterface $statusData, InitialInterface $initial)
    {
        $status = $initial->getStatus();
        $statusData->setStatusId($status);

        $statusLabel = $this->statusSource->getOptionLabel($status);
        if ($status == Status::STATUS_RUNNING) {
            $statusLabel .= __(', started at %1', $initial->getCreatedAt());
        }

        $statusData->setStatusLabel($statusLabel);

        return $statusData;
    }
}
