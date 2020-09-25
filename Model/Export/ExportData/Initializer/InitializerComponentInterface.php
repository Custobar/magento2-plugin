<?php

namespace Custobar\CustoConnector\Model\Export\ExportData\Initializer;

use Custobar\CustoConnector\Api\Data\ExportDataInterface;

interface InitializerComponentInterface
{
    /**
     * Intended for constructing export data before actually executing the export
     *
     * @param ExportDataInterface $exportData
     * @return ExportDataInterface
     */
    public function execute(ExportDataInterface $exportData);
}
