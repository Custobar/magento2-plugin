<?php

namespace Custobar\CustoConnector\Model;

use Custobar\CustoConnector\Api\EntityTypeResolverInterface;
use Custobar\CustoConnector\Model\EntityTypeResolver\ResolverComponentInterface;
use Magento\Framework\Validation\ValidationException;

class EntityTypeResolver implements EntityTypeResolverInterface
{
    /**
     * @var ResolverComponentInterface[]
     */
    private $components;

    /**
     * @param ResolverComponentInterface[] $components
     */
    public function __construct(
        array $components = []
    ) {
        $this->components = $components;
    }

    /**
     * @inheritDoc
     */
    public function resolveEntityType($entity)
    {
        foreach ($this->components as $name => $resolverComponent) {
            if ($resolverComponent === null) {
                continue;
            }
            if (!($resolverComponent instanceof ResolverComponentInterface)) {
                throw new ValidationException(__(
                    'Entity type resolver \'%1\' is not valid',
                    $name
                ));
            }

            if ($resolverComponent->isMatch($entity)) {
                return $resolverComponent->getEntityType();
            }
        }

        return '';
    }
}
