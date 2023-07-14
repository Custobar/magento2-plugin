<?php

namespace Custobar\CustoConnector\Api\Data;

interface ExportDataInterface
{
    public const ENTITY_TYPE = 'entity_type';
    public const ALL_SCHEDULES = 'all_schedules';
    public const ATTEMPTED_SCHEDULE_IDS = 'attempted_schedule_ids';
    public const SUCCESSFUL_SCHEDULE_IDS = 'successful_schedule_ids';
    public const FAILED_SCHEDULE_IDS = 'failed_schedule_ids';
    public const MAPPING_DATA = 'mapping_data';
    public const MAPPED_DATA_ROWS = 'mapped_data_rows';
    public const REQUEST_DATA_JSON = 'request_data_json';
    public const RESPONSE_DATA_JSON = 'response_data_json';

    /**
     * Get export data entity type
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Set export data entity type
     *
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setEntityType(string $entityType);

    /**
     * Get all schedule instances for the export data
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface[]
     */
    public function getAllSchedules();

    /**
     * Set all schedule instances for the export data
     *
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface[] $schedules
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setAllSchedules(array $schedules);

    /**
     * Get all attempted schedule instance ids
     *
     * @return int[]
     */
    public function getAttemptedScheduleIds();

    /**
     * Set all attempted schedule instance ids
     *
     * @param int[] $scheduleIds
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setAttemptedScheduleIds(array $scheduleIds);

    /**
     * Get all successful schedule instance ids
     *
     * @return int[]
     */
    public function getSuccessfulScheduleIds();

    /**
     * Set all successful schedule instance ids
     *
     * @param int[] $scheduleIds
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setSuccessfulScheduleIds(array $scheduleIds);

    /**
     * Get all failed schedule instance ids
     *
     * @return int[]
     */
    public function getFailedScheduleIds();

    /**
     * Set all failed schedule instance ids
     *
     * @param int[] $scheduleIds
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setFailedScheduleIds(array $scheduleIds);

    /**
     * Get mapping data
     *
     * @return MappingDataInterface
     */
    public function getMappingData();

    /**
     * Set mapping data
     *
     * @param MappingDataInterface $mappingData
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setMappingData(MappingDataInterface $mappingData);

    /**
     * Get data rows with mapping data applied
     *
     * @return \Magento\Framework\DataObject[]
     */
    public function getMappedDataRows();

    /**
     * Set data rows with mapping data applied
     *
     * @param \Magento\Framework\DataObject[] $mappedDataRows
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setMappedDataRows(array $mappedDataRows);

    /**
     * Get JSON of the outgoing request
     *
     * @return string
     */
    public function getRequestDataJson();

    /**
     * Set JSON of the outgoing request
     *
     * @param string $requestDataJson
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setRequestDataJson(string $requestDataJson);

    /**
     * Get raw response as JSON
     *
     * @return string
     */
    public function getResponseDataJson();

    /**
     * Set raw response as JSON
     *
     * @param string $responseDataJson
     *
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setResponseDataJson(string $responseDataJson);
}
