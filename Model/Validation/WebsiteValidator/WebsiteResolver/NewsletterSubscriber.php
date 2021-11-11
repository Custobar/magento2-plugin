<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolver;

use Custobar\CustoConnector\Model\ResourceModel\WebsiteResource;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolverInterface;

class NewsletterSubscriber implements WebsiteResolverInterface
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
        $subWebsites = $this->websiteResource->getNewsletterSubscriberWebsiteIds($entityIds);
        foreach ($subWebsites as $subscriberId => $websiteId) {
            $subWebsites[$subscriberId] = [$websiteId];
        }

        return $subWebsites;
    }
}
