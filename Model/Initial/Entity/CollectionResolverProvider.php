<?php

namespace Custobar\CustoConnector\Model\Initial\Entity;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Validation\ValidationException;

class CollectionResolverProvider implements CollectionResolverProviderInterface
{
    /**
     * @var CollectionResolverInterface[]
     */
    private $resolvers;

    /**
     * @param CollectionResolverInterface[] $resolvers
     */
    public function __construct(
        array $resolvers = []
    ) {
        $this->resolvers = $resolvers;
    }

    /**
     * @inheritDoc
     */
    public function getResolver(string $entityType)
    {
        $resolver = $this->resolvers[$entityType] ?? null;
        if ($resolver === null) {
            throw new NotFoundException(__(
                'Collection resolver not found for type \'%1\'',
                $entityType
            ));
        }
        if (!($resolver instanceof CollectionResolverInterface)) {
            throw new ValidationException(__(
                'Collection resolver not valid for type \'%1\'',
                $entityType
            ));
        }

        return $resolver;
    }
}
