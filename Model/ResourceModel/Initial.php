<?php

namespace Custobar\CustoConnector\Model\ResourceModel;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Initial extends AbstractDb
{
    const MAIN_TABLE = 'custoconnector_initial';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, InitialInterface::INITIAL_ID);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeAll()
    {
        $connection = $this->getConnection();
        $connection->delete($this->getMainTable());

        return $this;
    }

    /**
     * @param string $entityType
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
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
     * @param string $entityType
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
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
     * @param string $entityType
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
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
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
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
