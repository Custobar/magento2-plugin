<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\Customer;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

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
        /** @var \Magento\Customer\Model\Customer $entity */

        $additionalData = [];
        $additionalData['custobar_created_at'] = $this->formatDate($entity->getCreatedAt());

        $defaultAddress = $entity->getPrimaryBillingAddress();
        if ($defaultAddress) {
            $additionalData['custobar_street'] = \implode(', ', $defaultAddress->getStreet());
            $additionalData['custobar_city'] = $defaultAddress->getCity();
            $additionalData['custobar_country_id'] = $defaultAddress->getCountryId();
            $additionalData['custobar_region'] = $defaultAddress->getRegion();
            $additionalData['custobar_postcode'] = $defaultAddress->getPostcode();
            $additionalData['custobar_telephone'] = $defaultAddress->getTelephone();
        }

        $entity->addData($additionalData);

        return $entity;
    }

    /**
     * @param string $createdAt
     * @return string
     */
    private function formatDate(string $createdAt)
    {
        $date = $this->timezone->scopeDate(null, $createdAt, true);

        return $date->format('c');
    }
}
