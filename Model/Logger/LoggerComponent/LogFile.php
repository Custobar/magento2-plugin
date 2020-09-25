<?php

namespace Custobar\CustoConnector\Model\Logger\LoggerComponent;

use Custobar\CustoConnector\Model\Logger\LoggerComponentInterface;
use Psr\Log\LoggerInterface;

class LogFile implements LoggerComponentInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $level, string $message, array $contextData)
    {
        $this->logger->log($level, $message, $contextData);
    }
}
