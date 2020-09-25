<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Custobar\CustoConnector\Model\ResourceModel\LogData as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class LogData extends AbstractModel implements LogDataInterface
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
    public function getLogId()
    {
        return (int)$this->getData(self::LOG_ID);
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
    public function getType()
    {
        return (string)$this->getData(self::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE, $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return (string)$this->getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message)
    {
        $this->setData(self::MESSAGE, $message);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getContextData()
    {
        return $this->getData(self::CONTEXT_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setContextData(array $contextData)
    {
        $this->setData(self::CONTEXT_DATA, $contextData);

        return $this;
    }
}
