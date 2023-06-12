<?php

namespace Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponent;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status as InitialStatus;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponentInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataInterface;
use Magento\Backend\Model\UrlInterface;

class AddActionData implements BuilderComponentInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    public function __construct(
        UrlInterface $urlBuilder,
        MappingDataProviderInterface $mappingDataProvider
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->mappingDataProvider = $mappingDataProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute(StatusDataInterface $statusData, InitialInterface $initial)
    {
        $entityType = $initial->getEntityType();
        $mappingData = $this->mappingDataProvider->getMappingDataByEntityType($entityType);
        $statusData->setLabel($mappingData->getLabel());

        $status = $initial->getStatus();

        $cancelUrl = $this->urlBuilder->getUrl('custobar/status/cancel', [
            'identifier' => $mappingData->getTargetField(),
        ]);
        $exportUrl = $this->urlBuilder->getUrl('custobar/status/export', [
            'identifier' => $mappingData->getTargetField(),
        ]);

        if ($status == InitialStatus::STATUS_RUNNING) {
            $statusData->setActionUrl($cancelUrl);
            $statusData->setActionLabel(__('Cancel'));
        }
        if ($status == InitialStatus::STATUS_IDLE) {
            $statusData->setActionUrl($exportUrl);
            $statusData->setActionLabel(__('Run'));
        }
        if ($status == InitialStatus::STATUS_PROCESSED) {
            $statusData->setActionUrl($exportUrl);
            $statusData->setActionLabel(__('Rerun'));
        }

        return $statusData;
    }
}
