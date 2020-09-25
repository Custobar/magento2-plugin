<?php

namespace Custobar\CustoConnector\Api;

interface ExecutionValidatorInterface
{
    /**
     * Intended for determining whether or not the module functionality is allowed to execute
     *
     * @return bool
     */
    public function canExecute();
}
