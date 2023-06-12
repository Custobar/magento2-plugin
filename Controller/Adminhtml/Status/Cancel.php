<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Status;

use Custobar\CustoConnector\Api\InitialRepositoryInterface;
use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Cancel extends Action
{
    /**
     * @var InitialRepositoryInterface
     */
    private $initialRepository;

    /**
     * @var MappingDataProviderInterface
     */
    private $mappingDataProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        InitialRepositoryInterface $initialRepository,
        MappingDataProviderInterface $mappingDataProvider,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->initialRepository = $initialRepository;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $cancelled = [];
            $mappingDataItems = $this->resolveMappingData();
            foreach ($mappingDataItems as $mappingDataItem) {
                $entityType = $mappingDataItem->getEntityType();
                try {
                    $initial = $this->initialRepository->getByEntityType($entityType);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
                $status = $initial->getStatus();
                if ($status == Status::STATUS_IDLE || $status == Status::STATUS_PROCESSED) {
                    continue;
                }

                $initial->setStatus(Status::STATUS_IDLE);
                if ($initial->getProcessedAt() != '0000-00-00 00:00:00') {
                    $initial->setStatus(Status::STATUS_PROCESSED);
                }

                $this->initialRepository->delete($initial);
                $cancelled[] = $entityType;
            }

            if (!empty($cancelled)) {
                $this->messageManager->addSuccessMessage(__('Successfully canceled all running exports'));

                return $this->_redirect('custobar/status/index');
            }

            $this->messageManager->addWarningMessage(__('No exports to cancel'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));

            $this->logger->error($e->getMessage(), [
                'exceptionTrace' => $e->getTrace(),
            ]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed to cancel export(s)'));

            $this->logger->error($e->getMessage(), [
                'exceptionTrace' => $e->getTrace(),
            ]);
        }

        return $this->_redirect('custobar/status/index');
    }

    /**
     * @return \Custobar\CustoConnector\Api\Data\MappingDataInterface[]
     * @throws LocalizedException
     */
    private function resolveMappingData()
    {
        $identifier = (string)$this->getRequest()->getParam('identifier');
        if ($identifier == 'all') {
            return $this->mappingDataProvider->getAllMappingData();
        }

        $mappingData = $this->mappingDataProvider->getMappingDataByTargetField($identifier);
        if (!$mappingData) {
            throw new LocalizedException(__(
                'Cannot start export for unconfigured type \'%1\'',
                $identifier
            ));
        }

        return [$mappingData];
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Custobar_CustoConnector::status');
    }
}
