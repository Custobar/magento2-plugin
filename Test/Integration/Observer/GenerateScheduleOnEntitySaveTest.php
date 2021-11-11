<?php

namespace Custobar\CustoConnector\Test\Integration\Observer;

use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\ScheduleRepository;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\StoreManager;
use \Magento\TestFramework\Helper\Bootstrap;

class GenerateScheduleOnEntitySaveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var Schedule
     */
    private $scheduleResource;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->get(ProductRepository::class);
        $this->scheduleRepository = $this->objectManager->get(ScheduleRepository::class);
        $this->storeManager = $this->objectManager->get(StoreManager::class);
        $this->customerFactory = $this->objectManager->get(CustomerFactory::class);
        $this->categoryFactory = $this->objectManager->get(CategoryFactory::class);
        $this->scheduleResource = $this->objectManager->get(Schedule::class);
        $this->orderFactory = $this->objectManager->get(OrderFactory::class);
        $this->subscriberFactory = $this->objectManager->get(SubscriberFactory::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testExecuteProductScheduled()
    {
        $this->scheduleResource->removeAll();
        $product = $this->productRepository->get('simple');
        $this->assertEquals('Simple Product', $product->getName());

        try {
            $this->scheduleRepository->getByData(
                \Magento\Catalog\Model\Product::class,
                $product->getId(),
                $this->storeManager->getStore()->getId()
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $updatedName = 'Updated name';
        $product->setName($updatedName);
        $product->setHasDataChanges(true);
        $product->save();

        $product = $this->productRepository->get('simple', false, null, true);
        $this->assertEquals($updatedName, $product->getName());

        $schedule = $this->scheduleRepository->getByData(
            \Magento\Catalog\Model\Product::class,
            $product->getId(),
            $this->storeManager->getStore()->getId()
        );

        $this->assertEquals($product->getId(), $schedule->getScheduledEntityId());
        $this->assertEquals(\Magento\Catalog\Model\Product::class, $schedule->getScheduledEntityType());
        $this->assertEquals($this->storeManager->getStore()->getId(), $schedule->getStoreId());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testExecuteProductNotScheduledForInsufficientConfig()
    {
        $this->scheduleResource->removeAll();
        $product = $this->productRepository->get('simple');
        $this->assertEquals('Simple Product', $product->getName());

        try {
            $this->scheduleRepository->getByData(
                \Magento\Catalog\Model\Product::class,
                $product->getId(),
                $this->storeManager->getStore()->getId()
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $updatedName = 'Updated name';
        $product->setName($updatedName);
        $product->setHasDataChanges(true);
        $product->save();

        $product = $this->productRepository->get('simple', false, null, true);
        $this->assertEquals($updatedName, $product->getName());

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage(\sprintf(
            'No schedule found for entity \'%s\', id \'%s\' and store \'%s\'',
            \Magento\Catalog\Model\Product::class,
            $product->getId(),
            $this->storeManager->getStore()->getId()
        ));
        $this->scheduleRepository->getByData(
            \Magento\Catalog\Model\Product::class,
            $product->getId(),
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 100
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testExecuteProductNotScheduledForUnAllowedWebsite()
    {
        $this->scheduleResource->removeAll();
        $product = $this->productRepository->get('simple');
        $this->assertEquals('Simple Product', $product->getName());

        try {
            $this->scheduleRepository->getByData(
                \Magento\Catalog\Model\Product::class,
                $product->getId(),
                $this->storeManager->getStore()->getId()
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $updatedName = 'Updated name';
        $product->setName($updatedName);
        $product->setHasDataChanges(true);
        $product->save();

        $product = $this->productRepository->get('simple', false, null, true);
        $this->assertEquals($updatedName, $product->getName());

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage(\sprintf(
            'No schedule found for entity \'%s\', id \'%s\' and store \'%s\'',
            \Magento\Catalog\Model\Product::class,
            $product->getId(),
            $this->storeManager->getStore()->getId()
        ));
        $this->scheduleRepository->getByData(
            \Magento\Catalog\Model\Product::class,
            $product->getId(),
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Customer/_files/customer_one_address.php
     */
    public function testExecuteCustomerScheduledViaAddress()
    {
        $this->scheduleResource->removeAll();

        $customer = $this->customerFactory->create()
            ->setWebsiteId(1)
            ->loadByEmail('customer_one_address@test.com');
        $address = $customer->getDefaultBillingAddress();
        $this->assertEquals('CustomerAddress1', $address->getStreetFull());

        $updatedStreet = 'Updated street';
        $address->setStreetFull($updatedStreet);
        $address->setHasDataChanges(true);
        $address->save();

        $customer = $this->customerFactory->create()
            ->setWebsiteId(1)
            ->loadByEmail('customer_one_address@test.com');
        $address = $customer->getDefaultBillingAddress();
        $this->assertEquals($updatedStreet, $address->getStreetFull());

        $schedule = $this->scheduleRepository->getByData(
            \Magento\Customer\Model\Customer::class,
            $customer->getId(),
            $customer->getStoreId()
        );

        $this->assertEquals($customer->getId(), $schedule->getScheduledEntityId());
        $this->assertEquals(\Magento\Customer\Model\Customer::class, $schedule->getScheduledEntityType());
        $this->assertEquals($customer->getStoreId(), $schedule->getStoreId());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Catalog/_files/category.php
     */
    public function testExecuteUnmappedType()
    {
        $this->scheduleResource->removeAll();

        $category = $this->categoryFactory->create()
            ->loadByAttribute('name', 'Category 1');
        $updatedName = 'Updated name';
        $category->setName($updatedName);
        $category->setHasDataChanges(true);
        $category->save();

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage(\sprintf(
            'No schedule found for entity \'%s\', id \'%s\' and store \'%s\'',
            \Magento\Catalog\Model\Category::class,
            $category->getId(),
            $this->storeManager->getStore()->getId()
        ));
        $this->scheduleRepository->getByData(
            \Magento\Catalog\Model\Category::class,
            $category->getId(),
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Customer/_files/customer_one_address.php
     */
    public function testExecuteCustomerScheduled()
    {
        $this->scheduleResource->removeAll();
        $customer = $this->customerFactory->create()
            ->setWebsiteId(1)
            ->loadByEmail('customer_one_address@test.com');
        $this->assertEquals('John', $customer->getDataModel()->getFirstname());

        try {
            $this->scheduleRepository->getByData(
                \Magento\Customer\Model\Customer::class,
                $customer->getId(),
                $this->storeManager->getStore()->getId()
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $updatedFieldValue = 'New first name';
        $dataModel = $customer->getDataModel();
        $dataModel->setFirstname($updatedFieldValue);
        $customer->updateData($dataModel);
        $customer->setHasDataChanges(true);
        $customer->save();

        $customer = $this->customerFactory->create()
            ->setWebsiteId(1)
            ->loadByEmail('customer_one_address@test.com');
        $this->assertEquals($updatedFieldValue, $customer->getDataModel()->getFirstname());

        $schedule = $this->scheduleRepository->getByData(
            \Magento\Customer\Model\Customer::class,
            $customer->getId(),
            $this->storeManager->getStore()->getId()
        );

        $this->assertEquals($customer->getId(), $schedule->getScheduledEntityId());
        $this->assertEquals(\Magento\Customer\Model\Customer::class, $schedule->getScheduledEntityType());
        $this->assertEquals($this->storeManager->getStore()->getId(), $schedule->getStoreId());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testExecuteOrderScheduled()
    {
        $this->scheduleResource->removeAll();
        $order = $this->orderFactory->create()
            ->loadByIncrementId('100000001');
        $this->assertEquals(100, $order->getSubtotal());

        try {
            $this->scheduleRepository->getByData(
                \Magento\Sales\Model\Order::class,
                $order->getId(),
                $this->storeManager->getStore()->getId()
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $newSubTotal = 200;
        $order->setSubtotal($newSubTotal);
        $order->setHasDataChanges(true);
        $order->save();

        $order = $this->orderFactory->create()
            ->loadByIncrementId('100000001');
        $this->assertEquals($newSubTotal, $order->getSubtotal());

        $schedule = $this->scheduleRepository->getByData(
            \Magento\Sales\Model\Order::class,
            $order->getId(),
            $order->getStoreId()
        );

        $this->assertEquals($order->getId(), $schedule->getScheduledEntityId());
        $this->assertEquals(\Magento\Sales\Model\Order::class, $schedule->getScheduledEntityType());
        $this->assertEquals($order->getStoreId(), $schedule->getStoreId());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     */
    public function testExecuteNewsletterSubscriberScheduled()
    {
        $this->scheduleResource->removeAll();
        $subscriber = $this->subscriberFactory->create()
            ->loadByEmail('customer@example.com');
        $this->assertEquals(
            \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED,
            $subscriber->getStatus()
        );

        try {
            $this->scheduleRepository->getByData(
                \Magento\Newsletter\Model\Subscriber::class,
                $subscriber->getId(),
                $this->storeManager->getStore()->getId()
            );
            $this->assertTrue(false, 'Assert that exception was thrown');
        } catch (NoSuchEntityException $exception) {
            $this->assertTrue(true, 'Assert that exception was thrown');
        }

        $updatedStatus = \Magento\Newsletter\Model\Subscriber::STATUS_UNSUBSCRIBED;
        $subscriber->setSubscriberStatus($updatedStatus);
        $subscriber->save();

        $subscriber = $this->subscriberFactory->create()
            ->loadByEmail('customer@example.com');
        $this->assertEquals($updatedStatus, $subscriber->getStatus());

        $schedule = $this->scheduleRepository->getByData(
            \Magento\Newsletter\Model\Subscriber::class,
            $subscriber->getId(),
            $subscriber->getStoreId()
        );

        $this->assertEquals($subscriber->getId(), $schedule->getScheduledEntityId());
        $this->assertEquals(\Magento\Sales\Model\Order::class, $schedule->getScheduledEntityType());
        $this->assertEquals($subscriber->getStoreId(), $schedule->getStoreId());
    }
}
