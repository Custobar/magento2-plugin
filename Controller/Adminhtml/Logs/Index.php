<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;

class Index extends Action
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Custobar_CustoConnector::logs');
    }
}
