<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\ExportInterface;
use Custobar\CustoConnector\Model\Export\ExportData\InitializerInterface;
use Custobar\CustoConnector\Model\Export\ExportData\ProcessorInterface;

class Export implements ExportInterface
{
    /**
     * @var InitializerInterface
     */
    private $dataInitializer;

    /**
     * @var ProcessorInterface
     */
    private $exportProcessor;

    /**
     * @param InitializerInterface $dataInitializer
     * @param ProcessorInterface $exportProcessor
     */
    public function __construct(
        InitializerInterface $dataInitializer,
        ProcessorInterface $exportProcessor
    ) {
        $this->dataInitializer = $dataInitializer;
        $this->exportProcessor = $exportProcessor;
    }

    /**
     * @inheritDoc
     */
    public function exportBySchedules(array $schedules)
    {
        $exportDataByTypes = $this->dataInitializer->initializeBySchedules($schedules);
        foreach ($exportDataByTypes as $entityType => $exportData) {
            $exportData = $this->exportProcessor->execute($exportData);
            $exportDataByTypes[$entityType] = $exportData;
        }

        return $exportDataByTypes;
    }
}
