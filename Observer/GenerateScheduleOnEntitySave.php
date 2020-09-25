<?php

namespace Custobar\CustoConnector\Observer;

use Custobar\CustoConnector\Api\EntityDataConverterInterface;
use Custobar\CustoConnector\Api\ExecutionValidatorInterface;
use Custobar\CustoConnector\Api\ScheduleGeneratorInterface;
use Custobar\CustoConnector\Api\SchedulingValidatorInterface;
use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class GenerateScheduleOnEntitySave implements ObserverInterface
{
    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

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
     * @var EntityDataConverterInterface
     */
    private $dataConverter;

    public function __construct(
        EntityTypeResolverInterface $typeResolver,
        LoggerInterface $logger,
        SchedulingValidatorInterface $schedulingValidator,
        ScheduleGeneratorInterface $scheduleGenerator,
        ExecutionValidatorInterface $executionValidator,
        EntityDataConverterInterface $dataConverter
    ) {
        $this->typeResolver = $typeResolver;
        $this->logger = $logger;
        $this->schedulingValidator = $schedulingValidator;
        $this->scheduleGenerator = $scheduleGenerator;
        $this->executionValidator = $executionValidator;
        $this->dataConverter = $dataConverter;
    }

    public function execute(Observer $observer)
    {
        try {
            if (!$this->executionValidator->canExecute()) {
                return;
            }

            $entity = $observer->getObject();
            // If necessary, could check here if data has actually changed -> likely no point in scheduling if not

            $entity = $this->dataConverter->convertToMappedEntity($entity);
            if (!$entity) {
                return;
            }

            $entityType = $this->typeResolver->resolveEntityType($entity);
            if (!$entityType) {
                return;
            }

            if (!$this->schedulingValidator->canScheduleEntity($entity)) {
                $this->logger->debug("Rejected tracking item {$entity->getId()} of {$entityType}");

                return;
            }

            $this->scheduleGenerator->generateByEntity($entity);
        } catch (\Exception $e) {
            $this->logger->debug("Model after save failed {$e->getMessage()}", [
                'exceptionTrace' => $e->getTrace(),
            ]);
        }
    }
}
