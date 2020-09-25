<?php

namespace Custobar\CustoConnector\Setup;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Initial;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Db\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @inheritDoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createScheduleTable($setup);
        $this->createInitialTable($setup);

        $setup->endSetup();
    }

    private function createScheduleTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $scheduleTableName = $setup->getTable(Schedule::MAIN_TABLE);
        if ($connection->isTableExists($scheduleTableName)) {
            return;
        }

        $scheduleTable = $connection->newTable($scheduleTableName)
            ->addColumn(
                ScheduleInterface::SCHEDULE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                ],
                'inventory ID'
            )
            ->addColumn(
                ScheduleInterface::SCHEDULED_ENTITY_ID,
                Table::TYPE_INTEGER,
                10,
                [],
                'Id of the entity stored'
            )
            ->addColumn(
                ScheduleInterface::SCHEDULED_ENTITY_TYPE,
                Table::TYPE_TEXT,
                255,
                [],
                'Type of the entity stored'
            )
            ->addColumn(
                ScheduleInterface::STORE_ID,
                Table::TYPE_INTEGER,
                0,
                [
                    'nullable' => false,
                ],
                'Store view id when the event happened'
            )
            ->addColumn(
                ScheduleInterface::CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT,
                ],
                'Created Time'
            )
            ->addColumn(
                ScheduleInterface::PROCESSED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => '0000-00-00 00:00:00',
                ],
                'Finish Time'
            )
            ->addColumn(
                ScheduleInterface::ERROR_COUNT,
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                0,
                [
                    'nullable' => false,
                ],
                'Cumulative error count'
            )
            ->addIndex(
                $setup->getIdxName(
                    Schedule::MAIN_TABLE,
                    [
                        ScheduleInterface::SCHEDULED_ENTITY_TYPE,
                        ScheduleInterface::SCHEDULED_ENTITY_ID,
                        ScheduleInterface::STORE_ID,
                        ScheduleInterface::PROCESSED_AT,
                    ],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [
                    ScheduleInterface::SCHEDULED_ENTITY_TYPE,
                    ScheduleInterface::SCHEDULED_ENTITY_ID,
                    ScheduleInterface::STORE_ID,
                    ScheduleInterface::PROCESSED_AT,
                ],
                [
                    'type' => AdapterInterface::INDEX_TYPE_UNIQUE,
                ]
            );

        $connection->createTable($scheduleTable);
    }

    private function createInitialTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $initialTableName = $setup->getTable(Initial::MAIN_TABLE);
        if ($connection->isTableExists($initialTableName)) {
            return;
        }

        $initialTable = $connection->newTable($initialTableName)
            ->addColumn(
                InitialInterface::INITIAL_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                ],
                'initial id'
            )
            ->addColumn(
                InitialInterface::PAGE,
                Table::TYPE_INTEGER,
                null,
                [],
                'current page'
            )
            ->addColumn(
                InitialInterface::PAGES,
                Table::TYPE_INTEGER,
                null,
                [],
                'total pages'
            )
            ->addColumn(
                InitialInterface::ENTITY_TYPE,
                Table::TYPE_TEXT,
                255,
                [],
                'Type of the entity stored'
            )
            ->addColumn(
                InitialInterface::CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT,
                ],
                'Created Time'
            )
            ->addIndex(
                $setup->getIdxName(
                    Initial::MAIN_TABLE,
                    [
                        InitialInterface::ENTITY_TYPE,
                    ],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [
                    InitialInterface::ENTITY_TYPE,
                ],
                [
                    'type' => AdapterInterface::INDEX_TYPE_UNIQUE,
                ]
            );

        $connection->createTable($initialTable);
    }
}
