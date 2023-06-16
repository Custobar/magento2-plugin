<?php

namespace Custobar\CustoConnector\Cron;

use Custobar\CustoConnector\Api\ExecutionValidatorInterface;
use Custobar\CustoConnector\Api\ExportInterface;
use Custobar\CustoConnector\Model\Schedule\ExportableProviderInterface;

class ExportToCustobar
{
    /**
     * @var ExportInterface
     */
    private $export;

    /**
     * @var ExportableProviderInterface
     */
    private $exportableProvider;

    /**
     * @var ExecutionValidatorInterface
     */
    private $executionValidator;

    /**
     * @param ExportInterface $export
     * @param ExportableProviderInterface $exportableProvider
     * @param ExecutionValidatorInterface $executionValidator
     */
    public function __construct(
        ExportInterface $export,
        ExportableProviderInterface $exportableProvider,
        ExecutionValidatorInterface $executionValidator
    ) {
        $this->export = $export;
        $this->exportableProvider = $exportableProvider;
        $this->executionValidator = $executionValidator;
    }

    /**
     * Execute cron logic
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->executionValidator->canExecute()) {
            return;
        }

        $schedules = $this->exportableProvider->getSchedulesForExport();
        $this->export->exportBySchedules($schedules);
    }
}
