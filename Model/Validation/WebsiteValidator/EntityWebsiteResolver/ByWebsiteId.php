<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolver;

use Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolverInterface;

class ByWebsiteId implements EntityWebsiteResolverInterface
{
    /**
     * @inheritDoc
     */
    public function getEntityWebsiteIds($entity)
    {
        $websiteId = $entity->getWebsiteId();
        if (!empty($websiteId)) {
            return [$websiteId];
        }

        return [];
    }
}
