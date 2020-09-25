<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Status;

use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilderInterface;
use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Get extends Action
{
    /**
     * @var InitialRepositoryInterface
     */
    private $statusDataBuilder;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    public function __construct(
        Context $context,
        StatusDataBuilderInterface $statusDataBuilder,
        MappingDataProviderInterface $mappingDataProvider,
        LoggerInterface $logger,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->statusDataBuilder = $statusDataBuilder;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->logger = $logger;
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

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Custobar_CustoConnector::status');
    }
}
