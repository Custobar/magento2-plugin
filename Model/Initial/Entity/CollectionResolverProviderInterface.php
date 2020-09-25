<?php

namespace Custobar\CustoConnector\Model\Initial\Entity;

interface CollectionResolverProviderInterface
{
    /**
     * @param string $entityType
     * @return \Custobar\CustoConnector\Model\Initial\Entity\CollectionResolverInterface
     */
    public function getResolver(string $entityType);
}
