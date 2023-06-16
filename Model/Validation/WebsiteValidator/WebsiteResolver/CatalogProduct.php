<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolver;

use Custobar\CustoConnector\Model\ResourceModel\WebsiteResource;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolverInterface;

class CatalogProduct implements WebsiteResolverInterface
{
    /**
     * @var WebsiteResource
     */
    private $websiteResource;

    /**
     * @param WebsiteResource $websiteResource
     */
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
        return $this->websiteResource->getProductWebsiteIds($entityIds);
    }
}
