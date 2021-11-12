<?php

namespace Custobar\CustoConnector\Plugin;

use Custobar\CustoConnector\Api\ExecutionValidatorInterface;
use Custobar\CustoConnector\Api\ScheduleGeneratorInterface;
use Custobar\CustoConnector\Api\SchedulingValidatorInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Magento\Catalog\Model\Indexer\Product\Price\Action\Rows;
use Magento\Store\Model\StoreManagerInterface;

class GenerateSchedulesOnPriceIndexing
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SchedulingValidatorInterface
     */
    private $schedulingValidator;

    /**
     * @var ScheduleGeneratorInterface
     */
    private $scheduleGenerator;

    /**
     * @var ExecutionValidatorInterface
     */
    private $executionValidator;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        LoggerInterface $logger,
        SchedulingValidatorInterface $schedulingValidator,
        ScheduleGeneratorInterface $scheduleGenerator,
        ExecutionValidatorInterface $executionValidator,
        StoreManagerInterface $storeManager
    ) {
        $this->logger = $logger;
        $this->schedulingValidator = $schedulingValidator;
        $this->scheduleGenerator = $scheduleGenerator;
        $this->executionValidator = $executionValidator;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Rows $subject
     * @param mixed $result
     * @param mixed[] $ids
     *
     * @return mixed
     */
    public function afterExecute(
        Rows $subject,
        $result,
        $ids
    ) {
        if (!$this->executionValidator->canExecute() || !$ids) {
            return $result;
        }

        $scheduleIds = [];
        $entityType = \Magento\Catalog\Model\Product::ENTITY;
        $validationResults = $this->schedulingValidator->canScheduleEntityTypeAndIds($ids, $entityType);
        foreach ($validationResults as $entityId => $validationResult) {
            if ($validationResult) {
                $scheduleIds[] = $entityId;
                continue;
            }

            $this->logger->debug("Rejected tracking item {$entityId} of {$entityType}");
        }

        $stores = $this->storeManager->getStores();
        foreach ($scheduleIds as $scheduleId) {
            try {
                foreach ($stores as $store) {
                    $this->scheduleGenerator->generateByData($scheduleId, $store->getId(), $entityType);
                }
            } catch (\Exception $exception) {
                $this->logger->debug("Generate schedules on price indexing failed {$exception->getMessage()}", [
                    'exceptionTrace' => $exception->getTrace(),
                ]);
            }
        }

        return $result;
    }
}
