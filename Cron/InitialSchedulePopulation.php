<?php

namespace Custobar\CustoConnector\Cron;

use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\InitialRunnerInterface;
use Custobar\CustoConnector\Model\Schedule\LockControlInterface;

class InitialSchedulePopulation
{
    /**
     * @var InitialRunnerInterface
     */
    private $initialRunner;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var LockControlInterface
     */
    private $lockControl;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param InitialRunnerInterface $initialRunner
     * @param MappingDataProviderInterface $mappingDataProvider
     * @param LockControlInterface $lockControl
     * @param LoggerInterface $logger
     */
    public function __construct(
        InitialRunnerInterface $initialRunner,
        MappingDataProviderInterface $mappingDataProvider,
        LockControlInterface $lockControl,
        LoggerInterface $logger
    ) {
        $this->initialRunner = $initialRunner;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->lockControl = $lockControl;
        $this->logger = $logger;
    }

    /**
     * Execute cron logic
     *
     * @return void
     */
    public function execute()
    {
        if ($this->lockControl->isLocked()) {
            return;
        }

        $mappingDataItems = $this->mappingDataProvider->getAllMappingData();
        $this->lockControl->lock();

        foreach ($mappingDataItems as $mappingDataItem) {
            try {
                $this->initialRunner->runInitialByMappingData($mappingDataItem);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), [
                    'exceptionTrace' => $e->getTrace(),
                ]);
            }
        }

        $this->lockControl->unlock();
    }
}
