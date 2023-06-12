<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Model\ResourceModel\Initial as ResourceModel;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class InitialRepository implements InitialRepositoryInterface
{
    /**
     * @var InitialFactory
     */
    private $entityFactory;

    /**
     * @var ResourceModel
     */
    private $resourceModel;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var InitialInterface[]
     */
    private $cachedEntities;

    public function __construct(
        InitialFactory $entityFactory,
        ResourceModel $resourceModel,
        LoggerInterface $logger
    ) {
        $this->entityFactory = $entityFactory;
        $this->resourceModel = $resourceModel;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function get(int $initialId)
    {
        if (!isset($this->cachedEntities[$initialId])) {
            $entity = $this->entityFactory->create();
            $entity->load($initialId);

            if (!$entity->getId()) {
                throw new NoSuchEntityException(__('No initial found with id \'%1\'', $initialId));
            }

            $this->cachedEntities[$initialId] = $entity;
        }

        return $this->cachedEntities[$initialId];
    }

    /**
     * @inheritDoc
     */
    public function getByEntityType(string $entityType)
    {
        if (!isset($this->cachedEntities[$entityType])) {
            $existingId = $this->resourceModel->getExistingId($entityType);
            if (!$existingId) {
                throw new NoSuchEntityException(__(
                    'No initial found for entity type \'%1\'',
                    $entityType
                ));
            }

            $entity = $this->get($existingId);
            $this->cachedEntities[$entityType] = $entity;
        }

        return $this->cachedEntities[$entityType];
    }

    /**
     * @inheritDoc
     */
    public function save(InitialInterface $initial)
    {
        try {
            $existingId = $this->resourceModel->getExistingId($initial->getEntityType());
            if ($existingId > 0) {
                $initial->setInitialId($existingId);
            }

            $this->resourceModel->save($initial);
            $this->clearCached($initial);
        } catch (LocalizedException $e) {
            $this->logger->error($e);

            throw new CouldNotSaveException(__(
                'Failed to save initial \'%1\': %2',
                $initial->getInitialId(),
                $e->getMessage()
            ));
        } catch (\Exception $e) {
            $this->logger->error($e);

            throw new CouldNotSaveException(__(
                'Failed to save initial \'%1\'',
                $initial->getInitialId()
            ));
        }

        return $this->get($initial->getInitialId());
    }

    /**
     * @inheritDoc
     */
    public function delete(InitialInterface $initial)
    {
        $initialId = $initial->getInitialId();

        try {
            $this->clearCached($initial);
            $this->resourceModel->delete($initial);
        } catch (LocalizedException $e) {
            $this->logger->error($e);

            throw new CouldNotDeleteException(__(
                'Failed to delete initial \'%1\': %2',
                $initialId,
                $e->getMessage()
            ));
        } catch (\Exception $e) {
            $this->logger->error($e);

            throw new CouldNotDeleteException(__(
                'Failed to delete initial \'%1\'',
                $initialId
            ));
        }

        return true;
    }

    private function clearCached(InitialInterface $initial)
    {
        $initialId = $initial->getInitialId();
        if (isset($this->cachedEntities[$initialId])) {
            unset($this->cachedEntities[$initialId]);
        }
    }
}
