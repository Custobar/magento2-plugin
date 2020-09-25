<?php

namespace Custobar\CustoConnector\Model\LogData\Locator;

use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Custobar\CustoConnector\Model\LogData\LocatorInterface;
use Magento\Framework\App\RequestInterface;

class RequestLocator implements LocatorInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @return int
     */
    public function getCurrentLogId()
    {
        return (int)$this->request->getParam(LogDataInterface::LOG_ID, 0);
    }
}
