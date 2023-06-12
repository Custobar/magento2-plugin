<?php

namespace Custobar\CustoConnector\Model\Initial;

interface StatusDataInterface
{
    public const LABEL = 'label';
    public const STATUS_ID = 'status_id';
    public const STATUS_LABEL = 'status_label';
    public const EXPORT_PERCENT = 'export_percent';
    public const LAST_EXPORT_TIME = 'last_export_time';
    public const ACTION_URL = 'action_url';
    public const ACTION_LABEL = 'action_label';

    /**
     * Get status label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set status label
     *
     * @param string $label
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setLabel(string $label);

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId();

    /**
     * Set status id
     *
     * @param int $statusId
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setStatusId(int $statusId);

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel();

    /**
     * Set status label
     *
     * @param string $statusLabel
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setStatusLabel(string $statusLabel);

    /**
     * Get export percent
     *
     * @return string
     */
    public function getExportPercent();

    /**
     * Set export percent
     *
     * @param string $exportPercent
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setExportPercent(string $exportPercent);

    /**
     * Get last export time
     *
     * @return string
     */
    public function getLastExportTime();

    /**
     * Set last export time
     *
     * @param string $lastExportTime
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setLastExportTime(string $lastExportTime);

    /**
     * Get action url
     *
     * @return string
     */
    public function getActionUrl();

    /**
     * Set action url
     *
     * @param string $actionUrl
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setActionUrl(string $actionUrl);

    /**
     * Get action label
     *
     * @return string
     */
    public function getActionLabel();

    /**
     * Set action label
     *
     * @param string $actionLabel
     *
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setActionLabel(string $actionLabel);
}
