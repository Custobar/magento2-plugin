<?php

namespace Custobar\CustoConnector\Model\Initial;

interface PopulatorInterface
{
    /**
     * Creates initials for given entity types or all possible ones if empty given
     *
     * @param string[] $entityTypes
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface[]
     */
    public function execute(array $entityTypes = []);
}
