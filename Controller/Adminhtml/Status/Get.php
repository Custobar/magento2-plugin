<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Status;

use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilderInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Get extends Action
{
    public const ADMIN_RESOURCE = 'Custobar_CustoConnector::status';

    /**
     * @var InitialRepositoryInterface
     */
    private $statusDataBuilder;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @param Context $context
     * @param StatusDataBuilderInterface $statusDataBuilder
     * @param MappingDataProviderInterface $mappingDataProvider
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        StatusDataBuilderInterface $statusDataBuilder,
        MappingDataProviderInterface $mappingDataProvider,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->statusDataBuilder = $statusDataBuilder;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $json = $this->jsonFactory->create();

        $allStatusData = [];
        $mappingDataItems = $this->mappingDataProvider->getAllMappingData();
        foreach ($mappingDataItems as $mappingData) {
            $statusData = $this->statusDataBuilder->buildByMappingData($mappingData);
            $allStatusData[$mappingData->getTargetField()] = $statusData->getData();
        }

        $json->setData($allStatusData);

        return $json;
    }
}
