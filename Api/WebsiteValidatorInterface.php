<?php

namespace Custobar\CustoConnector\Api;

interface WebsiteValidatorInterface
{
    /**
     * Check if the given website needs to be acknowledged by the module
     *
     * @param int $websiteId
     *
     * @return bool
     */
    public function isWebsiteAllowed(int $websiteId);

    /**
     * Check if the given entity is in websites that need to be acknowledged by the module
     *
     * @param mixed $entity
     *
     * @return bool
     */
    public function isEntityInAllowedWebsites($entity);

    /**
     * Check if multiple entities are in websites that need to be acknowledged by the module
     *
     * @param string[] $entityIds
     * @param string $entityType
     *
     * @return bool[]
     */
    public function isEntityIdsInAllowedWebsites(array $entityIds, string $entityType);
}
