<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\SalesOrder;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Model\Order;

class AddBasicData implements DataExtenderInterface
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @param TimezoneInterface $timezone
     */
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
        /** @var Order $entity */
        $additionalData = [
            'custobar_created_at' => $this->formatDate((string)$entity->getCreatedAtDate()),
            'custobar_discount' => \round((float)$entity->getDiscountAmount() * 100),
            'custobar_grand_total' => \round((float)$entity->getGrandTotal() * 100),
        ];

        $payment = $entity->getPayment();
        if ($payment) {
            $additionalData['custobar_payment_method'] = $payment->getMethod();
        }
        $additionalData['custobar_state'] = \strtoupper($entity->getState());
        $entity->addData($additionalData);

        return $entity;
    }

    /**
     * Format date into ISO 8601
     *
     * @param string $createdAt
     *
     * @return string
     */
    private function formatDate(string $createdAt)
    {
        $date = $this->timezone->scopeDate(null, $createdAt, true);

        return $date->format('c');
    }
}
