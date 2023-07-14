<?php

namespace Custobar\CustoConnector\Model\Validation;

use Custobar\CustoConnector\Api\ExecutionValidatorInterface;
use Magento\Framework\Validation\ValidationException;

class ExecutionValidatorChain implements ExecutionValidatorInterface
{
    /**
     * @var ExecutionValidatorInterface[]
     */
    private $validators;

    /**
     * @param ExecutionValidatorInterface[] $validators
     */
    public function __construct(
        array $validators = []
    ) {
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function canExecute()
    {
        foreach ($this->validators as $name => $validator) {
            if ($validator === null) {
                continue;
            }
            if (!($validator instanceof ExecutionValidatorInterface)) {
                throw new ValidationException(__('Execution validator \'%1\' is not valid', $name));
            }

            if (!$validator->canExecute()) {
                return false;
            }
        }

        return true;
    }
}
