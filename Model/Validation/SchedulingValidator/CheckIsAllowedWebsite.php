<?php

namespace Custobar\CustoConnector\Model\Validation\SchedulingValidator;

use Custobar\CustoConnector\Api\SchedulingValidatorInterface;
use Custobar\CustoConnector\Api\WebsiteValidatorInterface;

class CheckIsAllowedWebsite implements SchedulingValidatorInterface
{
    /**
     * @var WebsiteValidatorInterface
     */
    private $websiteValidator;

    /**
     * @param WebsiteValidatorInterface $websiteValidator
     */
    public function __construct(
        WebsiteValidatorInterface $websiteValidator
    ) {
        $this->websiteValidator = $websiteValidator;
    }

    /**
     * @inheritDoc
     */
    public function canScheduleEntity($entity)
    {
        return $this->websiteValidator->isEntityInAllowedWebsites($entity);
    }

    /**
     * @inheritDoc
     */
    public function canScheduleEntityTypeAndIds(array $entityIds, string $entityType)
    {
        return $this->websiteValidator->isEntityIdsInAllowedWebsites($entityIds, $entityType);
    }
}
