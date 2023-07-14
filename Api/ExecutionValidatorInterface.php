<?php

namespace Custobar\CustoConnector\Api;

interface ExecutionValidatorInterface
{
    /**
     * Intended for determining if the module functionality is allowed to execute
     *
     * @return bool
     */
    public function canExecute();
}
