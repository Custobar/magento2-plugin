<?php

namespace Custobar\CustoConnector\Model\Export\ExportData;

use Custobar\CustoConnector\Api\Data\ExportDataInterface;

interface ProcessorInterface
{
    /**
     * Intended for processing initialized export data
     *
     * @param ExportDataInterface $exportData
     *
     * @return ExportDataInterface
     */
    public function execute(ExportDataInterface $exportData);
}
