<?php

namespace Custobar\CustoConnector\Model\MappedDataBuilder\DataExtender\SalesOrder;

use Custobar\CustoConnector\Model\MappedDataBuilder\DataExtenderInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class AddItemData implements DataExtenderInterface
{
    /**
     * @inheritDoc
     */
    public function execute($entity)
    {
        /** @var Order $entity */
        $itemsData = [];

        $visibleItems = $entity->getAllVisibleItems();
        /** @var Item $item */
        foreach ($visibleItems as $item) {
            $orderItem = [];
            $orderItem['sale_external_id'] = $entity->getIncrementId();
            $orderItem['external_id'] = $item->getId();
            $orderItem['product_id'] = $item->getSku();
            $orderItem['unit_price'] = \round((float)$item->getPriceInclTax() * 100);
            $orderItem['quantity'] = $item->getQtyOrdered();
            $orderItem['discount'] = \round((float)$item->getDiscountAmount() * 100);
            $orderItem['total'] = \round(
                (float)$item->getRowTotalInclTax() * 100
                - (float)$item->getDiscountAmount() * 100
            );

            $itemsData[] = $orderItem;
        }
        $entity->setData('custobar_order_items', $itemsData);

        return $entity;
    }
}
