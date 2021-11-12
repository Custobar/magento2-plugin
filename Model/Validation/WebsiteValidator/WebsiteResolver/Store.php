<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolver;

use Custobar\CustoConnector\Model\ResourceModel\WebsiteResource;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolverInterface;

class Store implements WebsiteResolverInterface
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
        $storeWebsites = $this->websiteResource->getStoreWebsiteIds($entityIds);
        foreach ($storeWebsites as $storeId => $websiteId) {
            $storeWebsites[$storeId] = [$websiteId];
        }

        return $storeWebsites;
    }
}
