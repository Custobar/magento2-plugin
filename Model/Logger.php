<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Model\Logger\LoggerComponentInterface;

class Logger implements LoggerInterface
{
    /**
     * @var LoggerComponentInterface[]
     */
    private $components;

    /**
     * @param LoggerComponentInterface[] $components
     */
    public function __construct(
        array $components = []
    ) {
        $this->components = $components;
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $contextData = [])
    {
        $this->log(\Monolog\Logger::DEBUG, $message, $contextData);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $contextData = [])
    {
        $this->log(\Monolog\Logger::INFO, $message, $contextData);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $contextData = [])
    {
        $this->log(\Monolog\Logger::WARNING, $message, $contextData);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $contextData = [])
    {
        $this->log(\Monolog\Logger::ERROR, $message, $contextData);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $contextData = [])
    {
        $this->log(\Monolog\Logger::ALERT, $message, $contextData);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $contextData = [])
    {
        $this->log(\Monolog\Logger::CRITICAL, $message, $contextData);
    }

    /**
     * @param int $level
     * @param string $message
     * @param mixed[] $contextData
     */
    private function log(int $level, string $message, array $contextData)
    {
        foreach ($this->components as $loggerComponent) {
            if (!($loggerComponent instanceof LoggerComponentInterface)) {
                continue;
            }

            $loggerComponent->execute($level, $message, $contextData);
        }
    }
}
