<?php

namespace Custobar\CustoConnector\Model\Validation\ExecutionValidator;

use Custobar\CustoConnector\Api\ExecutionValidatorInterface;
use Custobar\CustoConnector\Model\Config;

class CheckIfConfigAllows implements ExecutionValidatorInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function canExecute()
    {
        return ($this->config->getApiKey() && $this->config->getApiPrefix());
    }
}
