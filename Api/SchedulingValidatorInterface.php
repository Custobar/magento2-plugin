<?php

namespace Custobar\CustoConnector\Api;

interface SchedulingValidatorInterface
{
    /**
     * Check if schedule entity can be created for the given entity
     *
     * @param mixed $entity
     *
     * @return bool
     */
    public function canScheduleEntity($entity);

    /**
     * Check if schedules can be created based on detailed parameters
     *
     * @param string[] $entityIds
     * @param string $entityType
     *
     * @return bool[]
     */
    public function canScheduleEntityTypeAndIds(array $entityIds, string $entityType);
}
