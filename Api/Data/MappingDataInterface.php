<?php

namespace Custobar\CustoConnector\Api\Data;

interface MappingDataInterface
{
    public const ENTITY_TYPE = 'entity_type';
    public const LABEL = 'label';
    public const TARGET_FIELD = 'target_field';
    public const IS_INTERNAL = 'is_internal';
    public const FIELD_MAP = 'field_map';
    public const FIELD_MAP_CONFIG = 'field_map_config';

    /**
     * Get mapping entity type
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Set mapping entity type
     *
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setEntityType(string $entityType);

    /**
     * Get mapping label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set mapping label
     *
     * @param string $label
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setLabel(string $label);

    /**
     * Get target field for mapping
     *
     * @return string
     */
    public function getTargetField();

    /**
     * Set target field for mapping
     *
     * @param string $targetField
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setTargetField(string $targetField);

    /**
     * Check if the mapping is internal or external
     *
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsInternal();

    /**
     * Set if the mapping is internal or external
     *
     * @param bool $isInternal
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setIsInternal(bool $isInternal);

    /**
     * Get field map of the mapping
     *
     * @return mixed[]
     */
    public function getFieldMap();

    /**
     * Set field map of the mapping
     *
     * @param mixed[] $fieldMap
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setFieldMap(array $fieldMap);

    /**
     * Get config path for the field map of the mapping
     *
     * @return string
     */
    public function getFieldMapConfig();

    /**
     * Set config path for the field map of the mapping
     *
     * @param string $fieldMapConfig
     *
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface
     */
    public function setFieldMapConfig(string $fieldMapConfig);
}
