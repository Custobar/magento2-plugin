<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolver;

use Custobar\CustoConnector\Model\ResourceModel\WebsiteResource;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolverInterface;

class SalesOrder implements WebsiteResolverInterface
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
        $orderWebsites = $this->websiteResource->getOrderWebsiteIds($entityIds);
        foreach ($orderWebsites as $orderId => $websiteId) {
            $orderWebsites[$orderId] = [$websiteId];
        }

        return $orderWebsites;
    }
}
