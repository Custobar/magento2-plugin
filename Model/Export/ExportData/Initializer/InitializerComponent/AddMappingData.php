<?php

namespace Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponent;

use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponentInterface;
use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;

class AddMappingData implements InitializerComponentInterface
{
    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        MappingDataProviderInterface $mappingDataProvider,
        LoggerInterface $logger
    ) {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExportDataInterface $exportData)
    {
        $entityType = $exportData->getEntityType();
        $mappingData = $this->mappingDataProvider->getMappingDataByEntityType($entityType);
        if (empty($mappingData)) {
            $this->logger->debug(\__(
                'Did not process \'%1\', insufficient configurations',
                $entityType
            ));

            return $exportData;
        }

        $exportData->setMappingData($mappingData);

        return $exportData;
    }
}
