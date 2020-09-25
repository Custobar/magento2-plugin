<?php

namespace Custobar\CustoConnector\Api\Data;

interface InitialInterface
{
    const INITIAL_ID = 'id';
    const CREATED_AT = 'created_at';
    const PAGES = 'pages';
    const PAGE = 'page';
    const ENTITY_TYPE = 'entity_type';
    const PROCESSED_AT = 'processed_at';
    const STATUS = 'status';

    /**
     * @return int
     */
    public function getInitialId();

    /**
     * @param int $initialId
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setInitialId(int $initialId);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @return int
     */
    public function getPages();

    /**
     * @param int $pages
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     */
    public function setPages(int $pages);

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param int $page
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     */
    public function setPage(int $page);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $entityType
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     */
    public function setEntityType(string $entityType);

    /**
     * @return string
     */
    public function getProcessedAt();

    /**
     * @param string $processedAt
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setProcessedAt(string $processedAt);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setStatus(int $status);
}
