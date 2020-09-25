<?php

namespace Custobar\CustoConnector\Model\Initial;

interface StatusDataInterface
{
    const LABEL = 'label';
    const STATUS_ID = 'status_id';
    const STATUS_LABEL = 'status_label';
    const EXPORT_PERCENT = 'export_percent';
    const LAST_EXPORT_TIME = 'last_export_time';
    const ACTION_URL = 'action_url';
    const ACTION_LABEL = 'action_label';

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setLabel(string $label);

    /**
     * @return int
     */
    public function getStatusId();

    /**
     * @param int $statusId
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setStatusId(int $statusId);

    /**
     * @return string
     */
    public function getStatusLabel();

    /**
     * @param string $statusLabel
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setStatusLabel(string $statusLabel);

    /**
     * @return string
     */
    public function getExportPercent();

    /**
     * @param string $exportPercent
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setExportPercent(string $exportPercent);

    /**
     * @return string
     */
    public function getLastExportTime();

    /**
     * @param string $lastExportTime
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setLastExportTime(string $lastExportTime);

    /**
     * @return string
     */
    public function getActionUrl();

    /**
     * @param string $actionUrl
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setActionUrl(string $actionUrl);

    /**
     * @return string
     */
    public function getActionLabel();

    /**
     * @param string $actionLabel
     * @return \Custobar\CustoConnector\Model\Initial\StatusDataInterface
     */
    public function setActionLabel(string $actionLabel);
}
