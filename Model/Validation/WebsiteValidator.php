<?php

namespace Custobar\CustoConnector\Model\Validation;

use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Api\WebsiteValidatorInterface;
use Custobar\CustoConnector\Model\Config;
use Custobar\CustoConnector\Model\Validation\WebsiteValidator\EntityWebsiteResolverInterface;
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
     * @var EntityWebsiteResolverInterface[]
     */
    private $entityResolvers;

    /**
     * @param Config $config
     * @param EntityTypeResolverInterface $typeResolver
     * @param EntityWebsiteResolverInterface[] $entityResolvers
     */
    public function __construct(
        Config $config,
        EntityTypeResolverInterface $typeResolver,
        array $entityResolvers = []
    ) {
        $this->config = $config;
        $this->typeResolver = $typeResolver;
        $this->entityResolvers = $entityResolvers;
    }

    /**
     * @inheritDoc
     */
    public function isWebsiteAllowed(int $websiteId)
    {
        $websites = $this->config->getAllowedWebsites();
        $websites[] = 0;

        return \in_array($websiteId, $websites);
    }

    /**
     * @inheritDoc
     */
    public function isEntityInAllowedWebsites($entity)
    {
        $entityType = $this->typeResolver->resolveEntityType($entity);
        $websiteResolver = $this->entityResolvers[$entityType] ?? null;
        if ($websiteResolver === null) {
            return false;
        }
        if (!($websiteResolver instanceof EntityWebsiteResolverInterface)) {
            throw new ValidationException(\__('Website resolver for \'%1\' is not valid', $entityType));
        }

        $websiteIds = $websiteResolver->getEntityWebsiteIds($entity);
        foreach ($websiteIds as $id) {
            if ($this->isWebsiteAllowed($id)) {
                return true;
            }
        }

        return false;
    }
}
