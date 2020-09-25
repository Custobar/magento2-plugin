<?php

namespace Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolver;

use Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolverInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class ByStoreId implements EntityWebsiteResolverInterface
{
    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    public function __construct(
        StoreRepositoryInterface $storeRepository
    ) {
        $this->storeRepository = $storeRepository;
    }

    /**
     * @inheritDoc
     */
    public function getEntityWebsiteIds($entity)
    {
        $storeId = (int)$entity->getStoreId();
        $store = $this->storeRepository->getById($storeId);
        $websiteId = $store->getWebsiteId();

        if (!empty($websiteId)) {
            return [$websiteId];
        }

        return [];
    }
}
