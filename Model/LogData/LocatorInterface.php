<?php

namespace Custobar\CustoConnector\Model\LogData;

interface LocatorInterface
{
    /**
     * Intended for retrieving the current LogData entity's id from current context
     *
     * @return int
     */
    public function getCurrentLogId();
}
