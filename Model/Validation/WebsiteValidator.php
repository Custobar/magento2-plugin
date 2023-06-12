<?php

namespace Custobar\CustoConnector\Model\Validation;

use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\WebsiteValidatorInterface;
use Custobar\CustoConnector\Model\Config;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\WebsiteResolverInterface;
use Magento\Framework\Validation\ValidationException;

class WebsiteValidator implements WebsiteValidatorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var EntityTypeResolverInterface
     */
    private $typeResolver;

    /**
     * @var WebsiteResolverInterface[]
     */
    private $websiteResolvers;

    /**
     * @var bool[]
     */
    private $cachedResults;

    /**
     * @param Config $config
     * @param EntityTypeResolverInterface $typeResolver
     * @param WebsiteResolverInterface[] $websiteResolvers
     */
    public function __construct(
        Config $config,
        EntityTypeResolverInterface $typeResolver,
        array $websiteResolvers = []
    ) {
        $this->config = $config;
        $this->typeResolver = $typeResolver;
        $this->websiteResolvers = $websiteResolvers;
    }

    /**
     * @inheritDoc
     */
    public function isWebsiteAllowed(int $websiteId)
    {
        if (!isset($this->cachedResults[$websiteId])) {
            $websites = $this->config->getAllowedWebsites();
            $websites[] = 0;

            $this->cachedResults[$websiteId] = \in_array($websiteId, $websites);
        }

        return $this->cachedResults[$websiteId];
    }

    /**
     * @inheritDoc
     */
    public function isEntityInAllowedWebsites($entity)
    {
        if (!$entity) {
            return false;
        }

        $entityId = $entity->getId();
        $entityType = $this->typeResolver->resolveEntityType($entity);
        $results = $this->isEntityIdsInAllowedWebsites([$entityId], $entityType);

        return $results[$entityId] ?? false;
    }

    /**
     * @inheritDoc
     */
    public function isEntityIdsInAllowedWebsites(array $entityIds, string $entityType)
    {
        $websiteResolver = $this->websiteResolvers[$entityType] ?? null;
        if ($websiteResolver === null) {
            return \array_fill_keys($entityIds, false);
        }
        if (!($websiteResolver instanceof WebsiteResolverInterface)) {
            throw new ValidationException(__('Website resolver for \'%1\' is not valid', $entityType));
        }

        $validationResults = [];
        $entityWebsiteIds = $websiteResolver->getEntityWebsiteIds($entityIds);
        foreach ($entityWebsiteIds as $entityId => $websiteIds) {
            $validationResults[$entityId] = $this->isOneWebsiteAllowed($websiteIds);
        }

        return $validationResults;
    }

    /**
     * @param int[] $websiteIds
     *
     * @return bool
     */
    private function isOneWebsiteAllowed(array $websiteIds)
    {
        foreach ($websiteIds as $id) {
            if ($this->isWebsiteAllowed($id)) {
                return true;
            }
        }

        return false;
    }
}
