<?php

namespace Custobar\CustoConnector\Model\Export;

use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Custobar\CustoConnector\Api\Data\MappingDataInterface;
use Magento\Framework\DataObject;

class ExportData extends DataObject implements ExportDataInterface
{
    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return (string)$this->getData(self::ENTITY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setEntityType(string $entityType)
    {
        $this->setData(self::ENTITY_TYPE, $entityType);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAllSchedules()
    {
        return $this->getData(self::ALL_SCHEDULES) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setAllSchedules(array $schedules)
    {
        $this->setData(self::ALL_SCHEDULES, $schedules);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAttemptedScheduleIds()
    {
        return $this->getData(self::ATTEMPTED_SCHEDULE_IDS) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setAttemptedScheduleIds(array $scheduleIds)
    {
        $this->setData(self::ATTEMPTED_SCHEDULE_IDS, $scheduleIds);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessfulScheduleIds()
    {
        return $this->getData(self::SUCCESSFUL_SCHEDULE_IDS) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setSuccessfulScheduleIds(array $scheduleIds)
    {
        $this->setData(self::SUCCESSFUL_SCHEDULE_IDS, $scheduleIds);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFailedScheduleIds()
    {
        return $this->getData(self::FAILED_SCHEDULE_IDS) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setFailedScheduleIds(array $scheduleIds)
    {
        $this->setData(self::FAILED_SCHEDULE_IDS, $scheduleIds);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMappingData()
    {
        return $this->getData(self::MAPPING_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setMappingData(MappingDataInterface $mappingData)
    {
        $this->setData(self::MAPPING_DATA, $mappingData);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMappedDataRows()
    {
        return $this->getData(self::MAPPED_DATA_ROWS) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setMappedDataRows(array $mappedDataRows)
    {
        $this->setData(self::MAPPED_DATA_ROWS, $mappedDataRows);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRequestDataJson()
    {
        return (string)$this->getData(self::REQUEST_DATA_JSON);
    }

    /**
     * @inheritDoc
     */
    public function setRequestDataJson(string $requestDataJson)
    {
        $this->setData(self::REQUEST_DATA_JSON, $requestDataJson);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getResponseDataJson()
    {
        return (string)$this->getData(self::RESPONSE_DATA_JSON);
    }

    /**
     * @inheritDoc
     */
    public function setResponseDataJson(string $responseDataJson)
    {
        $this->setData(self::RESPONSE_DATA_JSON, $responseDataJson);

        return $this;
    }
}
