<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\MappingDataInterface;
use Magento\Framework\DataObject;

class MappingData extends DataObject implements MappingDataInterface
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
    public function setEntityType(string $entity)
    {
        $this->setData(self::ENTITY_TYPE, $entity);

        return $this;
    }

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
    public function getTargetField()
    {
        return (string)$this->getData(self::TARGET_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setTargetField(string $targetField)
    {
        $this->setData(self::TARGET_FIELD, $targetField);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIsInternal()
    {
        return (bool)$this->getData(self::IS_INTERNAL);
    }

    /**
     * @inheritDoc
     */
    public function setIsInternal(bool $isInternal)
    {
        $this->setData(self::IS_INTERNAL, $isInternal);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFieldMap()
    {
        return $this->getData(self::FIELD_MAP) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setFieldMap(array $fieldMap)
    {
        $this->setData(self::FIELD_MAP, $fieldMap);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFieldMapConfig()
    {
        return (string)$this->getData(self::FIELD_MAP_CONFIG);
    }

    /**
     * @inheritDoc
     */
    public function setFieldMapConfig(string $fieldMapConfig)
    {
        $this->setData(self::FIELD_MAP_CONFIG, $fieldMapConfig);

        return $this;
    }
}
