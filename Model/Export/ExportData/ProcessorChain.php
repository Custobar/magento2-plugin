<?php

namespace Custobar\CustoConnector\Model\Export\ExportData;

use Custobar\CustoConnector\Api\Data\ExportDataInterface;

class ProcessorChain implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(
        array $processors = []
    ) {
        $this->processors = $processors;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExportDataInterface $exportData)
    {
        foreach ($this->processors as $processor) {
            if (!($processor instanceof ProcessorInterface)) {
                continue;
            }

            $exportData = $processor->execute($exportData);
        }

        return $exportData;
    }
}
