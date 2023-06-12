<?php

namespace Custobar\CustoConnector\Model\Initial;

use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverProviderInterface;
use Custobar\CustoConnector\Model\InitialFactory;

class Populator implements PopulatorInterface
{
    public const DEFAULT_PAGE_SIZE = 500;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var InitialFactory
     */
    private $initialFactory;

    /**
     * @var InitialRepositoryInterface
     */
    private $initialRepository;

    /**
     * @var CollectionResolverProviderInterface
     */
    private $collectionResolver;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @param MappingDataProviderInterface $mappingDataProvider
     * @param InitialFactory $initialFactory
     * @param InitialRepositoryInterface $initialRepository
     * @param CollectionResolverProviderInterface $collectionResolver
     * @param int $pageSize
     */
    public function __construct(
        MappingDataProviderInterface $mappingDataProvider,
        InitialFactory $initialFactory,
        InitialRepositoryInterface $initialRepository,
        CollectionResolverProviderInterface $collectionResolver,
        int $pageSize = self::DEFAULT_PAGE_SIZE
    ) {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->initialFactory = $initialFactory;
        $this->initialRepository = $initialRepository;
        $this->collectionResolver = $collectionResolver;
        $this->pageSize = $pageSize;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $entityTypes = [])
    {
        $mappingDataItems = $this->mappingDataProvider->getAllMappingData();

        $initials = [];
        foreach ($mappingDataItems as $mappingDataItem) {
            $entityType = $mappingDataItem->getEntityType();
            if ($entityTypes && !\in_array($entityType, $entityTypes)) {
                continue;
            }

            $collectionResolver = $this->collectionResolver->getResolver($entityType);
            $collection = $collectionResolver->getCollection();
            if (!$collection->getSize()) {
                continue;
            }

            $collection->setPageSize($this->pageSize);

            /** @var \Custobar\CustoConnector\Api\Data\InitialInterface $initial */
            $initial = $this->initialFactory->create();
            $initial->setPage(0);
            $initial->setPages($collection->getLastPageNumber());
            $initial->setEntityType($entityType);
            $initial->setCreatedAt(\time());
            $initial->setStatus(Status::STATUS_RUNNING);

            $initial = $this->initialRepository->save($initial);
            $initials[$entityType] = $initial;
        }

        return $initials;
    }
}
