<?php

namespace Custobar\CustoConnector\Api\Data;

interface LogDataInterface
{
    const LOG_ID = 'log_id';
    const CREATED_AT = 'created_at';
    const TYPE = 'type';
    const MESSAGE = 'message';
    const CONTEXT_DATA = 'context_data';

    /**
     * @return int
     */
    public function getLogId();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return \Custobar\CustoConnector\Api\Data\LogDataInterface
     */
    public function setType(string $type);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return \Custobar\CustoConnector\Api\Data\LogDataInterface
     */
    public function setMessage(string $message);

    /**
     * @return mixed[]
     */
    public function getContextData();

    /**
     * @param mixed[] $contextData
     * @return \Custobar\CustoConnector\Api\Data\LogDataInterface
     */
    public function setContextData(array $contextData);
}
