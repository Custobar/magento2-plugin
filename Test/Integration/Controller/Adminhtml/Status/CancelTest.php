<?php

namespace Custobar\CustoConnector\Test\Integration\Controller\Adminhtml\Status;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Model\Initial\Config\Source\Status;
use Custobar\CustoConnector\Model\InitialRepository;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\MessageInterface;
use Magento\TestFramework\Helper\Bootstrap;

class CancelTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var InitialRepository
     */
    private $initialRepository;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->resource = 'Custobar_CustoConnector::status';
        $this->uri = 'backend/custobar/status/cancel';

        parent::setUp();

        $this->objectManager = Bootstrap::getObjectManager();
        $this->initialRepository = $this->objectManager->get(InitialRepository::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture loadInitialsForAllFixture
     */
    public function testCancel()
    {
        $this->assertInitials([
            \Magento\Catalog\Model\Product::class => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ]);

        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);
        $this->getRequest()->setPostValue(['identifier' => 'products']);
        $this->dispatch('backend/custobar/status/cancel');

        $this->assertSessionMessages(
            $this->equalTo(['Successfully canceled all running exports']),
            MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/custobar/status/index'));

        $this->assertInitials([
            \Magento\Catalog\Model\Product::class => null,
        ]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testCancelNoInitials()
    {
        $this->assertInitials([
            \Magento\Catalog\Model\Product::class => null,
        ]);

        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);
        $this->getRequest()->setPostValue(['identifier' => 'products']);
        $this->dispatch('backend/custobar/status/cancel');

        $this->assertSessionMessages(
            $this->equalTo([
                \sprintf(
                    'No initial found with id \'%s\'',
                    \Magento\Catalog\Model\Product::class
                ),
            ]),
            MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/custobar/status/index'));
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testCancelUnmappedType()
    {
        $this->assertInitials([
            'unknown_type' => null,
        ]);

        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);
        $this->getRequest()->setPostValue(['identifier' => 'unknown_type']);
        $this->dispatch('backend/custobar/status/cancel');

        $this->assertSessionMessages(
            $this->equalTo(['No initial found with id \'unknown_type\'']),
            MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/custobar/status/index'));
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture loadInitialsForAllFixture
     */
    public function testCancelAll()
    {
        $this->assertInitials([
            \Magento\Catalog\Model\Product::class => [
                InitialInterface::ENTITY_TYPE => \Magento\Catalog\Model\Product::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Customer\Model\Customer::class => [
                InitialInterface::ENTITY_TYPE => \Magento\Customer\Model\Customer::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Sales\Model\Order::class => [
                InitialInterface::ENTITY_TYPE => \Magento\Sales\Model\Order::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Newsletter\Model\Subscriber::class => [
                InitialInterface::ENTITY_TYPE => \Magento\Newsletter\Model\Subscriber::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
            \Magento\Store\Model\Store::class => [
                InitialInterface::ENTITY_TYPE => \Magento\Store\Model\Store::class,
                InitialInterface::STATUS => Status::STATUS_RUNNING,
            ],
        ]);

        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);
        $this->getRequest()->setPostValue(['identifier' => 'all']);
        $this->dispatch('backend/custobar/status/cancel');

        $this->assertSessionMessages(
            $this->equalTo(['Successfully canceled all running exports']),
            MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/custobar/status/index'));

        $this->assertInitials([
            \Magento\Catalog\Model\Product::class => null,
            \Magento\Customer\Model\Customer::class => null,
            \Magento\Sales\Model\Order::class => null,
            \Magento\Newsletter\Model\Subscriber::class => null,
            \Magento\Store\Model\Store::class => null,
        ]);
    }

    /**
     * @param mixed[] $allExpectedData
     * @throws NoSuchEntityException
     */
    private function assertInitials(array $allExpectedData)
    {
        // Recreated due to class scope caching
        $this->initialRepository = $this->objectManager->create(InitialRepository::class);

        foreach ($allExpectedData as $entityType => $expectedData) {
            if (!\is_array($expectedData)) {
                $this->expectException(NoSuchEntityException::class);
                $this->initialRepository->getByEntityType($entityType);

                continue;
            }

            $initial = $this->initialRepository->getByEntityType($entityType);
            foreach ($expectedData as $field => $expectedValue) {
                $realValue = $initial->getData($field);
                $this->assertEquals($expectedValue, $realValue, \sprintf(
                    'Assert that %s initial\'s field %s matches expected value',
                    $entityType,
                    $field
                ));
            }
        }
    }

    public static function loadInitialsForAllFixture()
    {
        include __DIR__ . '/../../../_files/initials_for_all.php';
    }

    public static function loadInitialsForAllFixtureRollback()
    {
        include __DIR__ . '/../../../_files/initials_for_all_rollback.php';
    }
}
