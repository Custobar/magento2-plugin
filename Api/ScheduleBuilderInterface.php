<?php

namespace Custobar\CustoConnector\Api;

interface ScheduleBuilderInterface
{
    /**
     * Create schedule entity by data in array
     *
     * @param mixed[] $scheduleData
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function buildByData(array $scheduleData);

    /**
     * Create schedule entity by another entity instance
     *
     * @param mixed $entity
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function buildByEntity($entity);
}
