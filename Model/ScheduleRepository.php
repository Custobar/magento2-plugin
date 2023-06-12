<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\ScheduleRepositoryInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule as ResourceModel;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    /**
     * @var ScheduleFactory
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
     * @var ScheduleInterface[]
     */
    private $cachedEntities;

    public function __construct(
        ScheduleFactory $entityFactory,
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
    public function get(int $scheduleId)
    {
        if (!isset($this->cachedEntities[$scheduleId])) {
            $entity = $this->entityFactory->create();
            $entity->load($scheduleId);

            if (!$entity->getId()) {
                throw new NoSuchEntityException(__('No schedule found with id \'%1\'', $scheduleId));
            }

            $this->cachedEntities[$scheduleId] = $entity;
        }

        return $this->cachedEntities[$scheduleId];
    }

    /**
     * @inheritDoc
     */
    public function getByData(string $entityType, int $entityId, int $storeId)
    {
        $cacheKey = \sprintf('%s-%s-%s', $entityType, $entityId, $storeId);
        if (!isset($this->cachedEntities[$cacheKey])) {
            $existingId = $this->resourceModel->getExistingId($entityType, $entityId, $storeId, '');
            if (!$existingId) {
                throw new NoSuchEntityException(__(
                    'No schedule found for entity \'%1\', id \'%2\' and store \'%3\'',
                    $entityType,
                    $entityId,
                    $storeId
                ));
            }

            $entity = $this->get($existingId);
            $this->cachedEntities[$cacheKey] = $entity;
        }

        return $this->cachedEntities[$cacheKey];
    }

    /**
     * @inheritDoc
     */
    public function save(ScheduleInterface $schedule)
    {
        try {
            $existingId = $this->resourceModel->getExistingId(
                $schedule->getScheduledEntityType(),
                $schedule->getScheduledEntityId(),
                $schedule->getStoreId()
            );
            if ($existingId > 0) {
                $schedule->setScheduleId($existingId);
            }

            $this->resourceModel->save($schedule);
            $this->clearCached($schedule);
        } catch (LocalizedException $e) {
            $this->logger->error($e);

            throw new CouldNotSaveException(__(
                'Failed to save schedule \'%1\': %2',
                $schedule->getScheduleId(),
                $e->getMessage()
            ));
        } catch (\Exception $e) {
            $this->logger->error($e);

            throw new CouldNotSaveException(__(
                'Failed to save schedule \'%1\'',
                $schedule->getScheduleId()
            ));
        }

        return $this->get($schedule->getScheduleId());
    }

    /**
     * @inheritDoc
     */
    public function delete(ScheduleInterface $schedule)
    {
        $scheduleId = $schedule->getScheduleId();

        try {
            $this->clearCached($schedule);
            $this->resourceModel->delete($schedule);
        } catch (LocalizedException $e) {
            $this->logger->error($e);

            throw new CouldNotDeleteException(__(
                'Failed to delete schedule \'%1\': %2',
                $scheduleId,
                $e->getMessage()
            ));
        } catch (\Exception $e) {
            $this->logger->error($e);

            throw new CouldNotDeleteException(__(
                'Failed to delete schedule \'%1\'',
                $scheduleId
            ));
        }

        return true;
    }

    private function clearCached(ScheduleInterface $schedule)
    {
        $scheduleId = $schedule->getScheduleId();
        if (isset($this->cachedEntities[$scheduleId])) {
            unset($this->cachedEntities[$scheduleId]);
        }
    }
}
