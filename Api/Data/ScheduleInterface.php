<?php

namespace Custobar\CustoConnector\Api\Data;

interface ScheduleInterface
{
    public const SCHEDULE_ID = 'id';
    public const CREATED_AT = 'created_at';
    public const STORE_ID = 'store_id';
    public const SCHEDULED_ENTITY_ID = 'entity_id';
    public const SCHEDULED_ENTITY_TYPE = 'entity_type';
    public const PROCESSED_AT = 'processed_at';
    public const ERROR_COUNT = 'errors';

    /**
     * Get schedule entity id
     *
     * @return int
     */
    public function getScheduleId();

    /**
     * Set schedule entity id
     *
     * @param int $scheduleId
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setScheduleId(int $scheduleId);

    /**
     * Get schedule creation time
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set schedule creation time
     *
     * @param string $createdAt
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * Get schedule store id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set schedule store id
     *
     * @param int $storeId
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setStoreId(int $storeId);

    /**
     * Get entity id related to the schedule
     *
     * @return int
     */
    public function getScheduledEntityId();

    /**
     * Set entity id related to the schedule
     *
     * @param int $entityId
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setScheduledEntityId(int $entityId);

    /**
     * Get entity type related to the schedule
     *
     * @return string
     */
    public function getScheduledEntityType();

    /**
     * Set entity type related to the schedule
     *
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setScheduledEntityType(string $entityType);

    /**
     * Get schedule processing time
     *
     * @return string
     */
    public function getProcessedAt();

    /**
     * Set schedule processing time
     *
     * @param string $processedAt
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setProcessedAt(string $processedAt);

    /**
     * Get number of failures to export for the schedule
     *
     * @return int
     */
    public function getErrorCount();

    /**
     * Set number of failures to export for the schedule
     *
     * @param int $errorCount
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setErrorCount(int $errorCount);
}
