<?php

namespace Custobar\CustoConnector\Model\Logger;

interface LoggerComponentInterface
{
    /**
     * Intended for executing the actual logging
     *
     * @param int $level
     * @param string $message
     * @param mixed[] $contextData
     * @return void
     */
    public function execute(int $level, string $message, array $contextData);
}
