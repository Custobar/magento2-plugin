<?php

namespace Custobar\CustoConnector\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

class WebsiteResource
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Get website ids for product ids
     *
     * @param int[] $productIds
     *
     * @return int[][]
     */
    public function getProductWebsiteIds(array $productIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('catalog_product_website'))
            ->where('product_id in (?)', $productIds);

        $productWebsites = [];
        $query = $connection->query($select);
        while ($row = $query->fetch()) {
            $productWebsites[$row['product_id']][] = $row['website_id'];
        }

        return $productWebsites;
    }

    /**
     * Get website ids for store ids
     *
     * @param int[] $storeIds
     *
     * @return int[]
     */
    public function getStoreWebsiteIds(array $storeIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('store'), ['store_id', 'website_id'])
            ->where('store_id in (?)', $storeIds);

        return $connection->fetchPairs($select);
    }

    /**
     * Get website ids for customer ids
     *
     * @param int[] $customerIds
     *
     * @return int[]
     */
    public function getCustomerWebsiteIds(array $customerIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('customer_entity'), ['entity_id', 'website_id'])
            ->where('entity_id in (?)', $customerIds);

        return $connection->fetchPairs($select);
    }

    /**
     * Get website ids for order ids
     *
     * @param int[] $orderIds
     *
     * @return int[]
     */
    public function getOrderWebsiteIds(array $orderIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('sales_order'), 'entity_id')
            ->where('entity_id in (?)', $orderIds)
            ->joinInner(
                'store',
                'store.store_id = sales_order.store_id',
                'store.website_id'
            );

        return $connection->fetchPairs($select);
    }

    /**
     * Get website ids for newsletter subscription ids
     *
     * @param int[] $subscriberIds
     *
     * @return int[]
     */
    public function getNewsletterSubscriberWebsiteIds(array $subscriberIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('newsletter_subscriber'), 'subscriber_id')
            ->where('subscriber_id in (?)', $subscriberIds)
            ->joinInner(
                'store',
                'store.store_id = newsletter_subscriber.store_id',
                'store.website_id'
            );

        return $connection->fetchPairs($select);
    }

    /**
     * Get connection instance
     *
     * @return AdapterInterface
     */
    private function getConnection()
    {
        if ($this->connection === null) {
            $this->connection = $this->resourceConnection->getConnection();
        }

        return $this->connection;
    }
}
