<?php

namespace Custobar\CustoConnector\Model\ResourceModel;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Zend_Db_Expr;

class Schedule extends AbstractDb
{
    const MAIN_TABLE = 'custoconnector_schedule';
    const MAX_ERROR_COUNT = '7200';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, ScheduleInterface::SCHEDULE_ID);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeProcessedSchedules()
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            [ScheduleInterface::PROCESSED_AT . ' != ?' => '0000-00-00 00:00:00']
        );

        return $this;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeAll()
    {
        $this->getConnection()->delete($this->getMainTable());

        return $this;
    }

    /**
     * @param string $entityType
     * @param int $entityId
     * @param int $storeId
     * @param string $processedAt
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getExistingId(
        string $entityType,
        int $entityId,
        int $storeId,
        string $processedAt = '0000-00-00 00:00:00'
    ) {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ScheduleInterface::SCHEDULE_ID)
            ->where(ScheduleInterface::SCHEDULED_ENTITY_TYPE . ' = ?', $entityType)
            ->where(ScheduleInterface::SCHEDULED_ENTITY_ID . ' = ?', $entityId)
            ->where(ScheduleInterface::STORE_ID . ' = ?', $storeId);
        if (!empty($processedAt)) {
            $select->where(ScheduleInterface::PROCESSED_AT . ' = ?', $processedAt);
        }

        return (int)$connection->fetchOne($select);
    }

    /**
     * @param int $scheduleId
     * @param int $errorCount
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateErrorCount(int $scheduleId, int $errorCount)
    {
        $connection = $this->getConnection();

        return $connection->update(
            $this->getMainTable(),
            [ScheduleInterface::ERROR_COUNT => $errorCount],
            [ScheduleInterface::SCHEDULE_ID . ' != ?' => $scheduleId]
        );
    }

    /**
     * @param int[] $scheduleIds
     * @param int $increment
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function increaseErrorCounts(array $scheduleIds, int $increment = 1)
    {
        if (empty($scheduleIds)) {
            return 0;
        }

        $connection = $this->getConnection();

        return $connection->update(
            $this->getMainTable(),
            [ScheduleInterface::ERROR_COUNT => new Zend_Db_Expr(\sprintf(
                '%s + %s',
                ScheduleInterface::ERROR_COUNT,
                $increment
            ))],
            \sprintf(
                '%s in (%s)',
                ScheduleInterface::SCHEDULE_ID,
                \implode(',', $scheduleIds)
            )
        );
    }

    /**
     * @param int[] $scheduleIds
     * @param string $processedAt
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateProcessedAt(array $scheduleIds, string $processedAt)
    {
        if (empty($scheduleIds)) {
            return 0;
        }

        $connection = $this->getConnection();

        return $connection->update(
            $this->getMainTable(),
            [ScheduleInterface::PROCESSED_AT => $processedAt],
            \sprintf(
                '%s in (%s)',
                ScheduleInterface::SCHEDULE_ID,
                \implode(',', $scheduleIds)
            )
        );
    }

    /**
     * @param int[] $scheduleIds
     * @return int[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function filterReschedulable(array $scheduleIds)
    {
        if (empty($scheduleIds)) {
            return [];
        }

        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ScheduleInterface::SCHEDULE_ID)
            ->where(\sprintf(
                '%s in (%s)',
                ScheduleInterface::SCHEDULE_ID,
                \implode(',', $scheduleIds)
            ))
            ->where(ScheduleInterface::ERROR_COUNT . ' < ?', self::MAX_ERROR_COUNT);

        return $connection->fetchCol($select);
    }
}
