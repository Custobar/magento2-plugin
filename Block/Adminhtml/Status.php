<?php

namespace Custobar\CustoConnector\Block\Adminhtml;

use Custobar\CustoConnector\Api\Data\MappingDataInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilderInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;
use Magento\Backend\Block\Template;

class Status extends Template
{
    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var StatusDataBuilderInterface
     */
    private $statusDataBuilder;

    /**
     * @param Template\Context $context
     * @param MappingDataProviderInterface $mappingDataProvider
     * @param StatusDataBuilderInterface $statusDataBuilder
     * @param mixed[] $data
     */
    public function __construct(
        Template\Context $context,
        MappingDataProviderInterface $mappingDataProvider,
        StatusDataBuilderInterface $statusDataBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->mappingDataProvider = $mappingDataProvider;
        $this->statusDataBuilder = $statusDataBuilder;
    }

    /**
     * Get all configured mapping data
     *
     * @return MappingDataInterface[]
     */
    public function getAllMappingData()
    {
        return $this->mappingDataProvider->getAllMappingData();
    }

    /**
     * Get status data instance for mapping data instance
     *
     * @param MappingDataInterface $mappingData
     *
     * @return StatusDataInterface
     */
    public function getStatusData(MappingDataInterface $mappingData)
    {
        return $this->statusDataBuilder->buildByMappingData($mappingData);
    }

    /**
     * Get run all link url
     *
     * @return string
     */
    public function getExportRunAllUrl()
    {
        return $this->getUrl('custobar/status/export', [
            'identifier' => 'all',
        ]);
    }

    /**
     * Get cancel all link url
     *
     * @return string
     */
    public function getExportCancelAllUrl()
    {
        return $this->getUrl('custobar/status/cancel', [
            'identifier' => 'all',
        ]);
    }

    /**
     * Get refresh link url
     *
     * @return string
     */
    public function getRefreshDataUrl()
    {
        return $this->getUrl('custobar/status/get', [
            'form_key' => $this->getFormKey(),
        ]);
    }
}
