<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\Store;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Store\Model\Store;

class AddBasicData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Store $entity */
        $websiteName = $entity->getWebsite()->getName();
        $groupName = $entity->getGroup()->getName();
        $storeName = $entity->getName();

        $entity->setData('custobar_name', \sprintf(
            '%s, %s, %s',
            $websiteName,
            $groupName,
            $storeName
        ));

        return $entity;
    }
}
