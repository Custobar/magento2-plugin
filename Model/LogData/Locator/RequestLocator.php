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

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentLogId()
    {
        return (int)$this->request->getParam(LogDataInterface::LOG_ID, 0);
    }
}
