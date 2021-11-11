<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolver;

use Custobar\CustoConnector\Model\ResourceModel\WebsiteResource;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolverInterface;

class Customer implements WebsiteResolverInterface
{
    /**
     * @var WebsiteResource
     */
    private $websiteResource;

    public function __construct(
        WebsiteResource $websiteResource
    ) {
        $this->websiteResource = $websiteResource;
    }

    /**
     * @inheritDoc
     */
    public function getEntityWebsiteIds(array $entityIds)
    {
        $customerWebsites = $this->websiteResource->getCustomerWebsiteIds($entityIds);
        foreach ($customerWebsites as $customerId => $websiteId) {
            $customerWebsites[$customerId] = [$websiteId];
        }

        return $customerWebsites;
    }
}
