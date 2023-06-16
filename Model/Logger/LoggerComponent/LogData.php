<?php

namespace Custobar\CustoConnector\Model\Logger\LoggerComponent;

use Custobar\CustoConnector\Model\Logger\LoggerComponentInterface;
use Custobar\CustoConnector\Model\ResourceModel\LogData as LogResource;

class LogData implements LoggerComponentInterface
{
    /**
     * @var LogResource
     */
    private $logResource;

    /**
     * @param LogResource $logResource
     */
    public function __construct(
        LogResource $logResource
    ) {
        $this->logResource = $logResource;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $level, string $message, array $contextData)
    {
        $this->logResource->addLog($level, $message, $contextData);
    }
}
