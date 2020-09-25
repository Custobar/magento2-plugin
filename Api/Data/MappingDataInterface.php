<?php

namespace Custobar\CustoConnector\Api\Data;

interface MappingDataInterface
{
    const ENTITY_TYPE = 'entity_type';
    const LABEL = 'label';
    const TARGET_FIELD = 'target_field';
    const IS_INTERNAL = 'is_internal';
    const FIELD_MAP = 'field_map';
    const FIELD_MAP_CONFIG = 'field_map_config';

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setEntityType(string $entityType);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setLabel(string $label);

    /**
     * @return string
     */
    public function getTargetField();

    /**
     * @param string $targetField
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setTargetField(string $targetField);

    /**
     * @return bool
     */
    public function getIsInternal();

    /**
     * @param bool $isInternal
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setIsInternal(bool $isInternal);

    /**
     * @return mixed[]
     */
    public function getFieldMap();

    /**
     * @param mixed[] $fieldMap
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setFieldMap(array $fieldMap);

    /**
     * @return string
     */
    public function getFieldMapConfig();

    /**
     * @param string $fieldMapConfig
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setFieldMapConfig(string $fieldMapConfig);
}
