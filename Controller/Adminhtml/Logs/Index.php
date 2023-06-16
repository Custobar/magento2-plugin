<?php

namespace Custobar\CustoConnector\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Custobar_CustoConnector::logs';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
