<?php

namespace Custobar\CustoConnector\Api\Data;

interface InitialInterface
{
    public const INITIAL_ID = 'id';
    public const CREATED_AT = 'created_at';
    public const PAGES = 'pages';
    public const PAGE = 'page';
    public const ENTITY_TYPE = 'entity_type';
    public const PROCESSED_AT = 'processed_at';
    public const STATUS = 'status';

    /**
     * Get initial's entity id
     *
     * @return int
     */
    public function getInitialId();

    /**
     * Set initial's entity id
     *
     * @param int $initialId
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setInitialId(int $initialId);

    /**
     * Get initial's creation time
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set initial's creation time
     *
     * @param string $createdAt
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * Get initial's number of pages
     *
     * @return int
     */
    public function getPages();

    /**
     * Set initial's number of pages
     *
     * @param int $pages
     *
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     */
    public function setPages(int $pages);

    /**
     * Get initial's current page
     *
     * @return int
     */
    public function getPage();

    /**
     * Set initial's current page
     *
     * @param int $page
     *
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     */
    public function setPage(int $page);

    /**
     * Get initial's related entity type
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Set initial's related entity type
     *
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     */
    public function setEntityType(string $entityType);

    /**
     * Get initial's processing time
     *
     * @return string
     */
    public function getProcessedAt();

    /**
     * Set initial's processing time
     *
     * @param string $processedAt
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setProcessedAt(string $processedAt);

    /**
     * Get initial's status code
     *
     * @return int
     */
    public function getStatus();

    /**
     * Set initial's status code
     *
     * @param int $status
     *
     * @return \Custobar\CustoConnector\Api\Data\ScheduleInterface
     */
    public function setStatus(int $status);
}
