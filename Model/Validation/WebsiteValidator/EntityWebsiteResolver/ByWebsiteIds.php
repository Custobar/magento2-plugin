<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolver;

use Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolverInterface;

class ByWebsiteIds implements EntityWebsiteResolverInterface
{
    /**
     * @inheritDoc
     */
    public function getEntityWebsiteIds($entity)
    {
        $websiteIds = $entity->getWebsiteIds();
        if (!empty($websiteIds)) {
            return $websiteIds;
        }

        return [];
    }
}
