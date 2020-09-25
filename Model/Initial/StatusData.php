<?php

namespace Custobar\CustoConnector\Model\Initial;

use Magento\Framework\DataObject;

class StatusData extends DataObject implements StatusDataInterface
{
    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return (string)$this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label)
    {
        $this->setData(self::LABEL, $label);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatusId()
    {
        return (int)$this->getData(self::STATUS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStatusId(int $statusId)
    {
        $this->setData(self::STATUS_ID, $statusId);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatusLabel()
    {
        return (string)$this->getData(self::STATUS_LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setStatusLabel(string $statusLabel)
    {
        $this->setData(self::STATUS_LABEL, $statusLabel);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getExportPercent()
    {
        return (string)$this->getData(self::EXPORT_PERCENT);
    }

    /**
     * @inheritDoc
     */
    public function setExportPercent(string $exportPercent)
    {
        $this->setData(self::EXPORT_PERCENT, $exportPercent);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLastExportTime()
    {
        return (string)$this->getData(self::LAST_EXPORT_TIME);
    }

    /**
     * @inheritDoc
     */
    public function setLastExportTime(string $lastExportTime)
    {
        $this->setData(self::LAST_EXPORT_TIME, $lastExportTime);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActionUrl()
    {
        return (string)$this->getData(self::ACTION_URL);
    }

    /**
     * @inheritDoc
     */
    public function setActionUrl(string $actionUrl)
    {
        $this->setData(self::ACTION_URL, $actionUrl);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActionLabel()
    {
        return (string)$this->getData(self::ACTION_LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setActionLabel(string $actionLabel)
    {
        $this->setData(self::ACTION_LABEL, $actionLabel);

        return $this;
    }
}
