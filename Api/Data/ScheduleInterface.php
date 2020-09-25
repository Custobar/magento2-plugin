<?php

namespace Custobar\CustoConnector\Api\Data;

interface ScheduleInterface
{
    const SCHEDULE_ID = 'id';
    const CREATED_AT = 'created_at';
    const STORE_ID = 'store_id';
    const SCHEDULED_ENTITY_ID = 'entity_id';
    const SCHEDULED_ENTITY_TYPE = 'entity_type';
    const PROCESSED_AT = 'processed_at';
    const ERROR_COUNT = 'errors';

    /**
     * @return int
     */
    public function getScheduleId();

    /**
     * @param int $scheduleId
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setScheduleId(int $scheduleId);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setStoreId(int $storeId);

    /**
     * @return int
     */
    public function getScheduledEntityId();

    /**
     * @param int $entityId
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setScheduledEntityId(int $entityId);

    /**
     * @return string
     */
    public function getScheduledEntityType();

    /**
     * @param string $entityType
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setScheduledEntityType(string $entityType);

    /**
     * @return string
     */
    public function getProcessedAt();

    /**
     * @param string $processedAt
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setProcessedAt(string $processedAt);

    /**
     * @return int
     */
    public function getErrorCount();

    /**
     * @param int $errorCount
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setErrorCount(int $errorCount);
}
