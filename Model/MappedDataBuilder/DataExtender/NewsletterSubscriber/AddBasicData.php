<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\NewsletterSubscriber;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Newsletter\Model\Subscriber;

class AddBasicData implements DataExtenderInterface
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var \Magento\Newsletter\Model\Subscriber $entity */

        $status = "MAIL_UNSUBSCRIBE";
        if ($entity->getStatus() == Subscriber::STATUS_SUBSCRIBED) {
            $status = "MAIL_SUBSCRIBE";
        }
        $entity->setData('custobar_status', $status);
        $entity->setData('custobar_date', \date('Y-m-d H:i:s', $this->timezone->scopeTimeStamp()));

        return $entity;
    }
}
