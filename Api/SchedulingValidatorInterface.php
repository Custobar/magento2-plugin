<?php

namespace Custobar\CustoConnector\Api;

interface SchedulingValidatorInterface
{
    /**
     * @param mixed $entity
     * @return bool
     */
    public function canScheduleEntity($entity);
}
