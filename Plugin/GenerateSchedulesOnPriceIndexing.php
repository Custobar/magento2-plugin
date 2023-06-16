<?php

namespace Custobar\CustoConnector\Plugin;

use Custobar\CustoConnector\Api\ExecutionValidatorInterface;
use Custobar\CustoConnector\Api\ScheduleGeneratorInterface;
use Custobar\CustoConnector\Api\SchedulingValidatorInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Magento\Catalog\Model\Indexer\Product\Price\Action\Rows;
use Magento\Catalog\Model\Product;
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

    /**
     * @param LoggerInterface $logger
     * @param SchedulingValidatorInterface $schedulingValidator
     * @param ScheduleGeneratorInterface $scheduleGenerator
     * @param ExecutionValidatorInterface $executionValidator
     * @param StoreManagerInterface $storeManager
     */
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
     * After price indexing runs, generate schedules for the indexed ids
     *
     * @param Rows $subject
     * @param mixed $result
     * @param mixed[] $ids
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
        $entityType = Product::class;
        $validationResults = $this->schedulingValidator->canScheduleEntityTypeAndIds($ids, $entityType);
        foreach ($validationResults as $entityId => $validationResult) {
            if ($validationResult) {
                $scheduleIds[] = $entityId;
                continue;
            }

            $this->logger->debug(__('Rejected tracking item %1 of %2', $entityId, $entityType));
        }

        $stores = $this->storeManager->getStores();
        foreach ($scheduleIds as $scheduleId) {
            try {
                foreach ($stores as $store) {
                    $this->scheduleGenerator->generateByData($scheduleId, $store->getId(), $entityType);
                }
            } catch (\Exception $exception) {
                $this->logger->debug(
                    __('Generate schedules on price indexing failed %1', $exception->getMessage()),
                    ['exceptionTrace' => $exception->getTrace()]
                );
            }
        }

        return $result;
    }
}
