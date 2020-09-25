<?php

namespace Custobar\CustoConnector\Api\Data;

interface ExportDataInterface
{
    const ENTITY_TYPE = 'entity_type';
    const ALL_SCHEDULES = 'all_schedules';
    const ATTEMPTED_SCHEDULE_IDS = 'attempted_schedule_ids';
    const SUCCESSFUL_SCHEDULE_IDS = 'successful_schedule_ids';
    const FAILED_SCHEDULE_IDS = 'failed_schedule_ids';
    const MAPPING_DATA = 'mapping_data';
    const MAPPED_DATA_ROWS = 'mapped_data_rows';
    const REQUEST_DATA_JSON = 'request_data_json';
    const RESPONSE_DATA_JSON = 'response_data_json';

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $entityType
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setEntityType(string $entityType);

    /**
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface[]
     */
    public function getAllSchedules();

    /**
     * @param \Custobar\CustoConnector\Api\Data\ScheduleInterface[] $schedules
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setAllSchedules(array $schedules);

    /**
     * @return int[]
     */
    public function getAttemptedScheduleIds();

    /**
     * @param int[] $scheduleIds
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setAttemptedScheduleIds(array $scheduleIds);

    /**
     * @return int[]
     */
    public function getSuccessfulScheduleIds();

    /**
     * @param int[] $scheduleIds
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setSuccessfulScheduleIds(array $scheduleIds);

    /**
     * @return int[]
     */
    public function getFailedScheduleIds();

    /**
     * @param int[] $scheduleIds
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setFailedScheduleIds(array $scheduleIds);

    /**
     * @return MappingDataInterface
     */
    public function getMappingData();

    /**
     * @param MappingDataInterface $mappingData
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setMappingData(MappingDataInterface $mappingData);

    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getMappedDataRows();

    /**
     * @param \Magento\Framework\DataObject[] $mappedDataRows
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setMappedDataRows(array $mappedDataRows);

    /**
     * @return string
     */
    public function getRequestDataJson();

    /**
     * @param string $requestDataJson
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setRequestDataJson(string $requestDataJson);

    /**
     * @return string
     */
    public function getResponseDataJson();

    /**
     * @param string $responseDataJson
     * @return \Custobar\CustoConnector\Api\Data\ExportDataInterface
     */
    public function setResponseDataJson(string $responseDataJson);
}
