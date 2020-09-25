<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Schedule extends AbstractModel implements ScheduleInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getScheduleId()
    {
        return (int)$this->getData(self::SCHEDULE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setScheduleId(int $scheduleId)
    {
        $this->setData(self::SCHEDULE_ID, $scheduleId);
        $this->setEntityId($scheduleId);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->setData(self::CREATED_AT, $createdAt);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return (int)$this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(int $storeId)
    {
        $this->setData(self::STORE_ID, $storeId);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getScheduledEntityId()
    {
        return (int)$this->getData(self::SCHEDULED_ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setScheduledEntityId(int $entityId)
    {
        $this->setData(self::SCHEDULED_ENTITY_ID, $entityId);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getScheduledEntityType()
    {
        return (string)$this->getData(self::SCHEDULED_ENTITY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setScheduledEntityType(string $entityType)
    {
        $this->setData(self::SCHEDULED_ENTITY_TYPE, $entityType);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getProcessedAt()
    {
        return (string)$this->getData(self::PROCESSED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setProcessedAt(string $processedAt)
    {
        $this->setData(self::PROCESSED_AT, $processedAt);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getErrorCount()
    {
        return (int)$this->getData(self::ERROR_COUNT);
    }

    /**
     * @inheritDoc
     */
    public function setErrorCount(int $errorCount)
    {
        $this->setData(self::ERROR_COUNT, $errorCount);

        return $this;
    }
}
