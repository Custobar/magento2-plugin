<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Status;

use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Api\MappingDataProviderInterface;
use Custobar\CustoConnector\Model\Initial\PopulatorInterface;
use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

class Export extends Action
{
    /**
     * @var PopulatorInterface
     */
    private $initialPopulator;

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
        PopulatorInterface $initialPopulator,
        MappingDataProviderInterface $mappingDataProvider,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->initialPopulator = $initialPopulator;
        $this->mappingDataProvider = $mappingDataProvider;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $entityTypes = [];
            $mappingDataItems = $this->resolveMappingData();
            foreach ($mappingDataItems as $mappingDataItem) {
                $entityTypes[] = $mappingDataItem->getEntityType();
            }
            $this->initialPopulator->execute($entityTypes);

            $this->messageManager->addSuccessMessage(__(
                'Successfully started export for %1 record types',
                \count($entityTypes)
            ));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));

            $this->logger->error($e->getMessage(), [
                'exceptionTrace' => $e->getTrace(),
            ]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed to start export(s)'));

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
        $identifier = $this->getRequest()->getParam('identifier');
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
