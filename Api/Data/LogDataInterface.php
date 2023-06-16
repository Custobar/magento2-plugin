<?php

namespace Custobar\CustoConnector\Api\Data;

interface LogDataInterface
{
    public const LOG_ID = 'log_id';
    public const CREATED_AT = 'created_at';
    public const TYPE = 'type';
    public const MESSAGE = 'message';
    public const CONTEXT_DATA = 'context_data';

    /**
     * Get log entity id
     *
     * @return int
     */
    public function getLogId();

    /**
     * Get log creation time
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Get log type
     *
     * @return string
     */
    public function getType();

    /**
     * Set log type
     *
     * @param string $type
     *
     * @return \Custobar\CustoConnector\Api\Data\LogDataInterface
     */
    public function setType(string $type);

    /**
     * Get log message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Set log message
     *
     * @param string $message
     *
     * @return \Custobar\CustoConnector\Api\Data\LogDataInterface
     */
    public function setMessage(string $message);

    /**
     * Get log context data
     *
     * @return mixed[]
     */
    public function getContextData();

    /**
     * Set log context data
     *
     * @param mixed[] $contextData
     *
     * @return \Custobar\CustoConnector\Api\Data\LogDataInterface
     */
    public function setContextData(array $contextData);
}
