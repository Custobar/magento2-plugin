<?php

namespace Custobar\CustoConnector\Model\Initial;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\MappingDataInterface;
use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Model\Initial\StatusDataBuilder\BuilderComponentInterface;
use Custobar\CustoConnector\Model\InitialFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;

class StatusDataBuilder implements StatusDataBuilderInterface
{
    /**
     * @var StatusDataFactory
     */
    private $statusDataFactory;

    /**
     * @var InitialFactory
     */
    private $initialFactory;

    /**
     * @var InitialRepositoryInterface
     */
    private $initialRepository;

    /**
     * @var BuilderComponentInterface[]
     */
    private $builderComponents;

    /**
     * @param StatusDataFactory $statusDataFactory
     * @param InitialFactory $initialFactory
     * @param InitialRepositoryInterface $initialRepository
     * @param BuilderComponentInterface[] $builderComponents
     */
    public function __construct(
        StatusDataFactory $statusDataFactory,
        InitialFactory $initialFactory,
        InitialRepositoryInterface $initialRepository,
        array $builderComponents = []
    ) {
        $this->statusDataFactory = $statusDataFactory;
        $this->initialFactory = $initialFactory;
        $this->initialRepository = $initialRepository;
        $this->builderComponents = $builderComponents;
    }

    /**
     * @inheritDoc
     */
    public function buildByMappingData(MappingDataInterface $mappingData)
    {
        $entityType = $mappingData->getEntityType();

        try {
            $initial = $this->initialRepository->getByEntityType($entityType);
        } catch (NoSuchEntityException $e) {
            $initial = $this->initialFactory->create();
            $initial->setEntityType($entityType);
        }

        return $this->buildByInitial($initial);
    }

    /**
     * @inheritDoc
     */
    public function buildByInitial(InitialInterface $initial)
    {
        /** @var StatusDataInterface $statusData */
        $statusData = $this->statusDataFactory->create();
        foreach ($this->builderComponents as $name => $builderComponent) {
            if ($builderComponent === null) {
                continue;
            }
            if (!($builderComponent instanceof BuilderComponentInterface)) {
                throw new ValidationException(__(
                    'Status data builder component \'%1\' is not valid',
                    $name
                ));
            }

            $statusData = $builderComponent->execute($statusData, $initial);
        }

        return $statusData;
    }
}
