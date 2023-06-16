<?php

namespace Custobar\CustoConnector\Model\ResourceModel;

use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Serialize\Serializer\Json;

class LogData extends AbstractDb
{
    public const MAIN_TABLE = 'custoconnector_log';

    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @param Context $context
     * @param Json $jsonSerializer
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        Json $jsonSerializer,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, LogDataInterface::LOG_ID);
    }

    /**
     * Check if log entry exists in database
     *
     * @param int $logId
     *
     * @return bool
     */
    public function isLogExists(int $logId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(self::MAIN_TABLE, LogDataInterface::LOG_ID)
            ->where(LogDataInterface::LOG_ID . ' = ?', $logId);

        return (bool)$connection->fetchOne($select);
    }

    /**
     * Add a log entry to database
     *
     * @param string $type
     * @param string $message
     * @param mixed[] $contextData
     * @throws LocalizedException
     */
    public function addLog(string $type, string $message, array $contextData = [])
    {
        $contextData = $this->jsonSerializer->serialize($contextData);
        $this->getConnection()->insert(
            $this->getMainTable(),
            [
                LogDataInterface::TYPE => $type,
                LogDataInterface::MESSAGE => $message,
                LogDataInterface::CONTEXT_DATA => $contextData,
            ]
        );
    }
}
