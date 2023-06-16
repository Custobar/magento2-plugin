<?php

namespace Custobar\CustoConnector\Model\ResourceModel;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Initial extends AbstractDb
{
    public const MAIN_TABLE = 'custoconnector_initial';

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, InitialInterface::INITIAL_ID);
    }

    /**
     * Delete all initials
     *
     * @return Initial
     * @throws LocalizedException
     */
    public function removeAll()
    {
        $connection = $this->getConnection();
        $connection->delete($this->getMainTable());

        return $this;
    }

    /**
     * Get existing initial entity id by entity type
     *
     * @param string $entityType
     *
     * @return int
     * @throws LocalizedException
     */
    public function getExistingId(string $entityType)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), InitialInterface::INITIAL_ID)
            ->where(InitialInterface::ENTITY_TYPE . ' = ?', $entityType);

        return (int)$connection->fetchOne($select);
    }

    /**
     * Check if there's any initial created for the given entity type
     *
     * @param string $entityType
     *
     * @return bool
     * @throws LocalizedException
     */
    public function isTypeExists(string $entityType)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where(InitialInterface::ENTITY_TYPE . ' = ?', $entityType);
        $rows = $connection->fetchCol($select);

        return !empty($rows);
    }

    /**
     * Delete all initials by entity type
     *
     * @param string $entityType
     * @return int
     * @throws LocalizedException
     */
    public function deleteType(string $entityType)
    {
        $connection = $this->getConnection();
        $result = $connection->delete(
            $this->getMainTable(),
            [InitialInterface::ENTITY_TYPE . ' = ?' => $entityType]
        );

        return $result;
    }

    /**
     * Check if any of the initials are in running state
     *
     * @return bool
     * @throws LocalizedException
     */
    public function isInitialRunning()
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'count(*)')
            ->where(InitialInterface::STATUS . ' = ?', Status::STATUS_RUNNING);
        $initialCount = (int)$connection->fetchOne($select);

        return $initialCount > 0;
    }
}
