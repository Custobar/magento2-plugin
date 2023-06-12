<?php

namespace Custobar\CustoConnector\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\MappingDataInterface;
use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Api\ScheduleGeneratorInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class InitialRunner implements InitialRunnerInterface
{
    public const DEFAULT_PAGE_SIZE = 500;

    /**
     * @var InitialRepositoryInterface
     */
    private $initialRepository;

    /**
     * @var CollectionResolverProviderInterface
     */
    private $collectionResolver;

    /**
     * @var ScheduleGeneratorInterface
     */
    private $scheduleGenerator;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @param InitialRepositoryInterface $initialRepository
     * @param CollectionResolverProviderInterface $collectionResolver
     * @param ScheduleGeneratorInterface $scheduleGenerator
     * @param int $pageSize
     */
    public function __construct(
        InitialRepositoryInterface $initialRepository,
        CollectionResolverProviderInterface $collectionResolver,
        ScheduleGeneratorInterface $scheduleGenerator,
        int $pageSize = self::DEFAULT_PAGE_SIZE
    ) {
        $this->initialRepository = $initialRepository;
        $this->collectionResolver = $collectionResolver;
        $this->scheduleGenerator = $scheduleGenerator;
        $this->pageSize = $pageSize;
    }

    /**
     * @inheritDoc
     */
    public function runInitialByMappingData(MappingDataInterface $mappingData)
    {
        $entityType = $mappingData->getEntityType();

        try {
            $initial = $this->initialRepository->getByEntityType($entityType);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        return $this->runInitial($initial);
    }

    /**
     * @inheritDoc
     */
    public function runInitial(InitialInterface $initial)
    {
        $status = $initial->getStatus();
        if ($status == Status::STATUS_IDLE || $status == Status::STATUS_PROCESSED) {
            return null;
        }

        $initial->setPage($initial->getPage() + 1);

        $collectionResolver = $this->collectionResolver->getResolver($initial->getEntityType());
        $collection = $collectionResolver->getCollection();
        $collection->setPageSize($this->pageSize);
        $collection->setCurPage($initial->getPage());

        foreach ($collection as $entity) {
            $this->scheduleGenerator->generateByEntity($entity);
        }

        $initial->setStatus(Status::STATUS_RUNNING);
        if ($initial->getPage() == $initial->getPages()) {
            $initial->setProcessedAt(\time());
            $initial->setStatus(Status::STATUS_PROCESSED);
        }

        return $this->initialRepository->save($initial);
    }
}
