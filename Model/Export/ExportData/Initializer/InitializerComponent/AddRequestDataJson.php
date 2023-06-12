<?php

namespace Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponent;

use Custobar\CustoConnector\Model\Export\ExportData\Initializer\InitializerComponentInterface;
use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;

class AddRequestDataJson implements InitializerComponentInterface
{
    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @param Json $jsonSerializer
     */
    public function __construct(
        Json $jsonSerializer
    ) {
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExportDataInterface $exportData)
    {
        $mappingData = $exportData->getMappingData();
        $mappedDataRows = $exportData->getMappedDataRows();
        if (!$mappingData || !$mappedDataRows) {
            return $exportData;
        }

        $arrayRows = [];
        foreach ($mappedDataRows as $mappedDataRow) {
            $arrayRows[] = $mappedDataRow->getData();
        }

        $requestDataJson = $this->jsonSerializer->serialize([
            $mappingData->getTargetField() => $arrayRows
        ]);

        $exportData->setRequestDataJson($requestDataJson);

        return $exportData;
    }
}
