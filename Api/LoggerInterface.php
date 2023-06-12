<?php

namespace Custobar\CustoConnector\Api;

interface LoggerInterface
{
    /**
     * Log debug level message
     *
     * @param string $message
     * @param mixed[] $contextData
     *
     * @return void
     */
    public function debug(string $message, array $contextData = []);

    /**
     * Log info level message
     *
     * @param string $message
     * @param mixed[] $contextData
     *
     * @return void
     */
    public function info(string $message, array $contextData = []);

    /**
     * Log warning level message
     *
     * @param string $message
     * @param mixed[] $contextData
     *
     * @return void
     */
    public function warning(string $message, array $contextData = []);

    /**
     * Log error level message
     *
     * @param string $message
     * @param mixed[] $contextData
     *
     * @return void
     */
    public function error(string $message, array $contextData = []);

    /**
     * Log alert level message
     *
     * @param string $message
     * @param mixed[] $contextData
     *
     * @return void
     */
    public function alert(string $message, array $contextData = []);

    /**
     * Log critical level message
     *
     * @param string $message
     * @param mixed[] $contextData
     *
     * @return void
     */
    public function critical(string $message, array $contextData = []);
}
