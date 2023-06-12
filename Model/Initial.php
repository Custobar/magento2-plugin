<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\ResourceModel\Initial as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Initial extends AbstractModel implements InitialInterface
{
    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getInitialId()
    {
        return (int)$this->getData(self::INITIAL_ID);
    }

    /**
     * @inheritDoc
     */
    public function setInitialId(int $initialId)
    {
        $this->setData(self::INITIAL_ID, $initialId);

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
    public function getPages()
    {
        return (int)$this->getData(self::PAGES);
    }

    /**
     * @inheritDoc
     */
    public function setPages(int $pages)
    {
        $this->setData(self::PAGES, $pages);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPage()
    {
        return (int)$this->getData(self::PAGE);
    }

    /**
     * @inheritDoc
     */
    public function setPage(int $page)
    {
        $this->setData(self::PAGE, $page);

        return $this;
    }

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
    public function getStatus()
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status)
    {
        $this->setData(self::STATUS, $status);

        return $this;
    }
}
