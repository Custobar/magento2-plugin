<?php

namespace Custobar\CustoConnector\Api;

interface ScheduleBuilderInterface
{
    /**
     * @param mixed[] $scheduleData
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function buildByData(array $scheduleData);

    /**
     * @param mixed $entity
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function buildByEntity($entity);
}
