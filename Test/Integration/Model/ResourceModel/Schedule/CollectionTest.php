<?php

namespace Custobar\CustoConnector\Test\Integration\Model\ResourceModel\Schedule;

use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use \Magento\TestFramework\Helper\Bootstrap;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
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
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture loadSchedulesCustomerFixture
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
            \Magento\Customer\Model\Customer::ENTITY,
            $first->getScheduledEntityType(),
            'Assert if type is as expected'
        );
        $this->assertEquals(
            1,
            $first->getStoreId(),
            'Assert if the store is as expected'
        );
    }

    public static function loadSchedulesCustomerFixture()
    {
        include __DIR__ . '/../../../_files/schedules_customer.php';
    }

    public static function loadSchedulesCustomerFixtureRollback()
    {
        include __DIR__ . '/../../../_files/schedules_customer_rollback.php';
    }
}
