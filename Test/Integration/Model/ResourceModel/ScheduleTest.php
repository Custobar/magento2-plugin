<?php

namespace Custobar\CustoConnector\Test\Integration\Model\ResourceModel\Schedule;

use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;

class ScheduleTest extends \PHPUnit\Framework\TestCase
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
     * @var Schedule
     */
    private $scheduleResource;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
        $this->scheduleResource = $this->objectManager->get(Schedule::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_customer.php
     */
    public function testRemoveProcessedSchedules()
    {
        $schedules = $this->collectionFactory->create()
            ->setPageSize(500)
            ->setCurPage(1);
        $data = $schedules->getData();
        $this->assertCount(3, $data);

        $this->scheduleResource->removeProcessedSchedules();

        $schedules->resetData()->clear();
        $data = $schedules->getData();
        $this->assertCount(2, $data);
    }
}
