<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Logs;

use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Custobar\CustoConnector\Model\ResourceModel\LogData;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var LogData
     */
    private $logResource;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        LogData $logResource
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->logResource = $logResource;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $logId = (int)$this->getRequest()->getParam(LogDataInterface::LOG_ID);
        if (!$this->logResource->isLogExists($logId)) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(\__('No log found with id \'%1\'', $logId));

            return $resultRedirect->setPath('custobar/logs/index');
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Custobar_CustoConnector::logs');
        $resultPage->getConfig()->getTitle()->prepend(\__('Log Entry #%1', $logId));

        return $resultPage;
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Custobar_CustoConnector::logs');
    }
}
