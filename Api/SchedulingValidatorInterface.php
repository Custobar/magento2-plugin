<?php

namespace Custobar\CustoConnector\Api;

interface SchedulingValidatorInterface
{
    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function canScheduleEntity($entity);

    /**
     * @param string[] $entityIds
     * @param string $entityType
     *
     * @return bool[]
     */
    public function canScheduleEntityTypeAndIds(array $entityIds, string $entityType);
}
