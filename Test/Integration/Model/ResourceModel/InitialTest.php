<?php

namespace Custobar\CustoConnector\Test\Integration\Model\ResourceModel\Schedule;

use Custobar\CustoConnector\Model\ResourceModel\Initial;
use Custobar\CustoConnector\Model\ResourceModel\Initial\CollectionFactory;
use \Magento\TestFramework\Helper\Bootstrap;

class InitialTest extends \PHPUnit\Framework\TestCase
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
     * @var Initial
     */
    private $initialResource;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
        $this->initialResource = $this->objectManager->get(Initial::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture loadInitialsForPartial
     */
    public function testRemoveAll()
    {
        $initials = $this->collectionFactory->create();
        $initialsCount = \count($initials->getItems());
        $this->assertEquals(2, $initialsCount);

        $this->initialResource->removeAll();

        $initials = $this->collectionFactory->create();
        $initialsCount = \count($initials->getItems());
        $this->assertEquals(0, $initialsCount);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture loadInitialsWithMixedStatus
     */
    public function testIsInitialRunningWithRunningInitials()
    {
        $this->assertTrue($this->initialResource->isInitialRunning());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture loadInitialsWithNonRunning
     */
    public function testIsInitialRunningWithNonRunningInitials()
    {
        $this->assertFalse($this->initialResource->isInitialRunning());
    }

    public static function loadInitialsForPartial()
    {
        include __DIR__ . '/../../_files/initials_for_partial.php';
    }

    public static function loadInitialsForPartialRollback()
    {
        include __DIR__ . '/../../_files/initials_for_partial_rollback.php';
    }

    public static function loadInitialsWithMixedStatus()
    {
        include __DIR__ . '/../../_files/initials_with_mixed_status.php';
    }

    public static function loadInitialsWithMixedStatusRollback()
    {
        include __DIR__ . '/../../_files/initials_with_mixed_status_rollback.php';
    }

    public static function loadInitialsWithNonRunning()
    {
        include __DIR__ . '/../../_files/initials_with_non_running.php';
    }

    public static function loadInitialsWithNonRunningRollback()
    {
        include __DIR__ . '/../../_files/initials_with_non_running_rollback.php';
    }
}
