<?php

namespace Custobar\CustoConnector\Api;

interface WebsiteValidatorInterface
{
    /**
     * @param int $websiteId
     *
     * @return bool
     */
    public function isWebsiteAllowed(int $websiteId);

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function isEntityInAllowedWebsites($entity);

    /**
     * @param string[] $entityIds
     * @param string $entityType
     *
     * @return bool[]
     */
    public function isEntityIdsInAllowedWebsites(array $entityIds, string $entityType);
}
