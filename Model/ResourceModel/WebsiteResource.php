<?php

namespace Custobar\CustoConnector\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class WebsiteResource
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int[] $productIds
     *
     * @return int[][]
     * @throws \Zend_Db_Statement_Exception
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
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private function getConnection()
    {
        if ($this->connection === null) {
            $this->connection = $this->resourceConnection->getConnection();
        }

        return $this->connection;
    }
}
