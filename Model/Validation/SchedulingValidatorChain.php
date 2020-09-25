<?php

namespace Custobar\CustoConnector\Model\Validation;

use Custobar\CustoConnector\Api\SchedulingValidatorInterface;
use Magento\Framework\Validation\ValidationException;

class SchedulingValidatorChain implements SchedulingValidatorInterface
{
    /**
     * @var SchedulingValidatorInterface[]
     */
    private $validators;

    /**
     * @param SchedulingValidatorInterface[] $validators
     */
    public function __construct(
        array $validators = []
    ) {
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function canScheduleEntity($entity)
    {
        foreach ($this->validators as $name => $validator) {
            if ($validator === null) {
                continue;
            }
            if (!($validator instanceof SchedulingValidatorInterface)) {
                throw new ValidationException(\__('Scheduling validator \'%1\' is not valid', $name));
            }

            if (!$validator->canScheduleEntity($entity)) {
                return false;
            }
        }

        return true;
    }
}
