<?php

namespace Custobar\CustoConnector\Test\Integration\Model;

use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder;
use Custobar\CustoConnector\Model\Export;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Custobar\CustoConnector\Model\Schedule\ExportableProvider;
use Custobar\CustoConnector\Model\ScheduleRepository;
use Laminas\Http\Headers;
use Laminas\Http\Response as HttpResponse;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Customer;
use Magento\Framework\HTTP\LaminasClient;
use Magento\Newsletter\Model\Subscriber;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

class ExportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var LaminasClient|MockObject
     */
    private $clientMock;

    /**
     * @var ClientBuilder|MockObject
     */
    private $clientBuilderMock;

    /**
     * @var ExportableProvider
     */
    private $exportableProvider;

    /**
     * @var Export
     */
    private $export;

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->exportableProvider = $this->objectManager->get(ExportableProvider::class);

        $this->clientMock = $this->createMock(LaminasClient::class);
        $this->clientBuilderMock = $this->createMock(ClientBuilder::class);

        $executeExport = $this->objectManager->create(Export\ExportData\Processor\ExecuteExport::class, [
            'clientBuilder' => $this->clientBuilderMock,
        ]);

        $processor = $this->objectManager->create(Export\ExportData\ProcessorChain::class, [
            'processors' => [
                'execute_export' => $executeExport,
                'adjust_schedules' => $this->objectManager->get(Export\ExportData\Processor\AdjustSchedules::class),
            ],
        ]);

        $this->export = $this->objectManager->create(Export::class, [
            'exportProcessor' => $processor,
        ]);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation disabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     *
     * @magentoDataFixture Magento/Customer/_files/three_customers.php
     * @magentoDataFixture Magento/Sales/_files/order_list.php
     * @magentoDataFixture Magento/Newsletter/_files/subscribers.php
     * @magentoDataFixture Magento/Catalog/_files/products_list.php
     *
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_customer.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_order.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_newsletter.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_product.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_store.php
     */
    public function testExportBySchedules()
    {
        $schedules = $this->exportableProvider->getSchedulesForExport();
        $idsByType = $this->getScheduleIdsByType($schedules);
        $scheduleCounts = [
            Product::class => 3,
            Customer::class => 2,
            Order::class => 2,
            Subscriber::class => 3,
            Store::class => 1,
        ];
        $this->assertScheduleCounts($scheduleCounts, $schedules);

        $this->clientBuilderMock->expects($this->any())->method('buildClient')
            ->willReturn($this->clientMock);
        $headers = new Headers();
        $headers->addHeaders([
            'Content-Type' => 'application/json',
        ]);
        $response = new HttpResponse();
        $response->setStatusCode(201);
        $response->setHeaders($headers);
        $response->setContent(\json_encode(['response' => 'ok']));
        $this->clientMock->expects($this->any())->method('send')
            ->willReturn($response);

        $allExpectedData = [
            Product::class => [
                ExportDataInterface::ENTITY_TYPE => Product::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => [],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => $idsByType[Product::class],
            ],
            Customer::class => [
                ExportDataInterface::ENTITY_TYPE => Customer::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => [],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => $idsByType[Customer::class],
            ],
            Order::class => [
                ExportDataInterface::ENTITY_TYPE => Order::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => [],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => $idsByType[Order::class],
            ],
            Subscriber::class => [
                ExportDataInterface::ENTITY_TYPE => Subscriber::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => [],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => $idsByType[Subscriber::class],
            ],
            Store::class => [
                ExportDataInterface::ENTITY_TYPE => Store::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => [],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => $idsByType[Store::class],
            ],
        ];
        $allExportData = $this->export->exportBySchedules($schedules);
        $this->assertExportData($allExpectedData, $allExportData);

        $schedules = $this->exportableProvider->getSchedulesForExport();
        $this->assertEquals(0, \count($schedules));
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     *
     * @magentoDataFixture Magento/Customer/_files/three_customers.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_customer_error_count.php
     */
    public function testExportBySchedulesMaxErrorCount()
    {
        $schedules = $this->exportableProvider->getSchedulesForExport();
        $idsByType = $this->getScheduleIdsByType($schedules);
        $scheduleCounts = [
            Customer::class => 2,
        ];
        $this->assertScheduleCounts($scheduleCounts, $schedules);
        $this->assertScheduleData([
            Customer::class => [
                1 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT - 1,
                    ],
                ],
                2 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => 5000,
                    ],
                ],
                3 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
            ],
        ]);

        $this->clientBuilderMock->expects($this->any())->method('buildClient')
            ->willReturn($this->clientMock);
        $headers = new Headers();
        $headers->addHeaders([
            'Content-Type' => 'application/json',
        ]);
        $response = new HttpResponse();
        $response->setStatusCode(201);
        $response->setHeaders($headers);
        $response->setContent(\json_encode(['error' => ['message' => ['test']]]));
        $this->clientMock->expects($this->any())->method('send')
            ->willReturn($response);

        $allExpectedData = [
            Customer::class => [
                ExportDataInterface::ENTITY_TYPE => Customer::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => $idsByType[Customer::class],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => [],
            ],
        ];
        $allExportData = $this->export->exportBySchedules($schedules);
        $this->assertExportData($allExpectedData, $allExportData);

        $this->assertScheduleData([
            Customer::class => [
                1 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
                2 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => 5001,
                    ],
                ],
                3 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
            ],
        ]);

        $schedules = $this->exportableProvider->getSchedulesForExport();
        $this->assertEquals(1, \count($schedules));
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     *
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/prefix prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/apikey prefixthatdoesntexists
     * @magentoConfigFixture default_store custobar/custobar_custoconnector/allowed_websites 1
     *
     * @magentoDataFixture Magento/Customer/_files/customer_one_address.php
     * @magentoDataFixture Custobar_CustoConnector::Test/Integration/_files/schedules_invalid.php
     */
    public function testExportBySchedulesInvalid()
    {
        $schedules = $this->exportableProvider->getSchedulesForExport();
        $idsByType = $this->getScheduleIdsByType($schedules);
        $scheduleCounts = [
            'unknown_type' => 1,
            Product::class => 1,
            Customer::class => 1,
            Address::class => 1,
        ];
        $this->assertScheduleCounts($scheduleCounts, $schedules);

        $this->clientBuilderMock->expects($this->never())->method('buildClient');
        $this->clientMock->expects($this->never())->method('send');

        $allExpectedData = [
            'unknown_type' => [
                ExportDataInterface::ENTITY_TYPE => 'unknown_type',
                ExportDataInterface::FAILED_SCHEDULE_IDS => $idsByType['unknown_type'],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => [],
                ExportDataInterface::MAPPED_DATA_ROWS => [],
            ],
            Product::class => [
                ExportDataInterface::ENTITY_TYPE => Product::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => $idsByType[Product::class],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => [],
                ExportDataInterface::MAPPED_DATA_ROWS => [],
            ],
            Customer::class => [
                ExportDataInterface::ENTITY_TYPE => Customer::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => $idsByType[Customer::class],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => [],
                ExportDataInterface::MAPPED_DATA_ROWS => null,
            ],
            Address::class => [
                ExportDataInterface::ENTITY_TYPE => Address::class,
                ExportDataInterface::FAILED_SCHEDULE_IDS => $idsByType[Address::class],
                ExportDataInterface::SUCCESSFUL_SCHEDULE_IDS => [],
                ExportDataInterface::MAPPED_DATA_ROWS => [],
            ],
        ];
        $allExportData = $this->export->exportBySchedules($schedules);
        $this->assertExportData($allExpectedData, $allExportData);

        $this->assertScheduleData([
            'unknown_type' => [
                1 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
            ],
            Product::class => [
                99999 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
            ],
            Customer::class => [
                1 => [
                    1000 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
            ],
            Address::class => [
                1 => [
                    1 => [
                        ScheduleInterface::ERROR_COUNT => Schedule::MAX_ERROR_COUNT,
                    ],
                ],
            ],
        ]);

        $schedules = $this->exportableProvider->getSchedulesForExport();
        $this->assertEquals(0, \count($schedules));
    }

    /**
     * @param mixed[] $allExpectedData
     * @param ExportDataInterface[] $allExportData
     */
    private function assertExportData(array $allExpectedData, array $allExportData)
    {
        foreach ($allExpectedData as $entityType => $expectedData) {
            $exportData = $allExportData[$entityType];
            foreach ($expectedData as $field => $expectedValue) {
                $realValue = $exportData->getData($field);
                $this->assertEquals($expectedValue, $realValue, \sprintf(
                    'Assert that %s export data\'s field %s matches expected value',
                    $entityType,
                    $field
                ));
            }
        }
    }

    /**
     * @param int[] $allExpectedCounts
     * @param ScheduleInterface[] $allSchedules
     */
    private function assertScheduleCounts(array $allExpectedCounts, array $allSchedules)
    {
        $schedulesByType = $this->groupSchedulesByType($allSchedules);

        $totalQty = 0;
        foreach ($allExpectedCounts as $entityType => $qty) {
            $schedules = $schedulesByType[$entityType];
            $scheduleCount = \count($schedules);
            $this->assertEquals($qty, $scheduleCount, \sprintf(
                'Assert that %s schedule count is expected value',
                $entityType
            ));

            $totalQty += $scheduleCount;
        }

        $this->assertEquals($totalQty, \count($allSchedules));
    }

    /**
     * @param mixed[] $allExpectedData
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function assertScheduleData(array $allExpectedData)
    {
        /** @var ScheduleRepository $scheduleRepository */
        $scheduleRepository = $this->objectManager->create(ScheduleRepository::class);

        foreach ($allExpectedData as $entityType => $entityData) {
            foreach ($entityData as $entityId => $storeData) {
                foreach ($storeData as $storeId => $expectedData) {
                    $schedule = $scheduleRepository->getByData($entityType, $entityId, $storeId);
                    foreach ($expectedData as $field => $expectedValue) {
                        $realValue = $schedule->getData($field);
                        $this->assertEquals($expectedValue, $realValue, \sprintf(
                            'Assert that %s %s schedule\'s field %s matches expected value',
                            $entityType,
                            $entityId,
                            $field
                        ));
                    }
                }
            }
        }
    }

    /**
     * @param ScheduleInterface[] $allSchedules
     * @return int[]
     */
    private function getScheduleIdsByType(array $allSchedules)
    {
        $idsByType = [];
        $schedulesByType = $this->groupSchedulesByType($allSchedules);
        foreach ($schedulesByType as $entityType => $schedules) {
            $scheduleIds = [];
            foreach ($schedules as $schedule) {
                $scheduleIds[] = $schedule->getId();
            }

            $idsByType[$entityType] = $scheduleIds;
        }

        return $idsByType;
    }

    /**
     * @param ScheduleInterface[] $allSchedules
     * @return mixed[]
     */
    private function groupSchedulesByType(array $allSchedules)
    {
        $schedulesByType = [];
        foreach ($allSchedules as $schedule) {
            $schedulesByType[$schedule->getScheduledEntityType()][] = $schedule;
        }

        return $schedulesByType;
    }
}
