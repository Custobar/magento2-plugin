<?php

namespace Custobar\CustoConnector\Block\Statistics\Config;

use Magento\Customer\Model\SessionFactory;
use Magento\Framework\View\Element\Template;

class Customer extends Template
{
    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * @param Template\Context $context
     * @param SessionFactory $sessionFactory
     * @param mixed[] $data
     */
    public function __construct(
        Template\Context $context,
        SessionFactory $sessionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * Get currently logged in customer id
     *
     * @return int|null
     */
    public function getCurrentCustomerId()
    {
        $customerSession = $this->sessionFactory->create();
        if ($customerSession->isLoggedIn()) {
            return (int)$customerSession->getCustomer()->getId();
        }

        return 0;
    }
}
