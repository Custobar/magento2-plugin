<?php

namespace Custobar\CustoConnector\Model\Initial\Entity;

interface CollectionResolverProviderInterface
{
    /**
     * Get entity collection resolver by entity type
     *
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverInterface
     */
    public function getResolver(string $entityType);
}
