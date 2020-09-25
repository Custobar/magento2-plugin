<?php

namespace Custobar\CustoConnector\Api;

interface LoggerInterface
{
    /**
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function debug(string $message, array $contextData = []);

    /**
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function info(string $message, array $contextData = []);

    /**
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function warning(string $message, array $contextData = []);

    /**
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function error(string $message, array $contextData = []);

    /**
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function alert(string $message, array $contextData = []);

    /**
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function critical(string $message, array $contextData = []);
}
