<?php

namespace Custobar\CustoConnector\Test\Integration\Model\ResourceModel\Schedule;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_customer.php
     */
    public function testScheduleFilters()
    {
        $schedules = $this->collectionFactory->create()
            ->setOrder(ScheduleInterface::SCHEDULED_ENTITY_ID, AbstractDb::SORT_ORDER_ASC)
            ->addOnlyForSendingFilter();

        /** @var ScheduleInterface $first */
        $first = $schedules->getFirstItem();

        $this->assertEquals(
            2,
            $first->getScheduledEntityId(),
            'Assert if id is as expected'
        );
        $this->assertEquals(
            Customer::class,
            $first->getScheduledEntityType(),
            'Assert if type is as expected'
        );
        $this->assertEquals(
            1,
            $first->getStoreId(),
            'Assert if the store is as expected'
        );
    }
}
