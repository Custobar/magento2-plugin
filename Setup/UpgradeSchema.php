<?php

namespace Custobar\CustoConnector\Setup;

use Custobar\CustoConnector\Api\Data\InitialInterface;
use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Custobar\CustoConnector\Api\Data\ScheduleInterface;
use Custobar\CustoConnector\Model\ResourceModel\Initial;
use Custobar\CustoConnector\Model\ResourceModel\LogData;
use Custobar\CustoConnector\Model\ResourceModel\Schedule;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Db\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @inheritDoc
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (\version_compare($context->getVersion(), '1.0.0') < 0) {
            $this->version100addLogTable($setup);
        }
        if (\version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->version101fixTimestampColumns($setup);
        }
        if (\version_compare($context->getVersion(), '1.1.0') < 0) {
            $this->version110addProcessedAtOnInitial($setup);
        }
        if (\version_compare($context->getVersion(), '1.2.0') < 0) {
            $this->version120addStatusOnInitial($setup);
        }

        $setup->endSetup();
    }

    private function version100addLogTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(LogData::MAIN_TABLE);
        $connection = $setup->getConnection();
        if ($connection->isTableExists($tableName)) {
            return;
        }

        $table = $connection->newTable($tableName);
        $table->addColumn(
            LogDataInterface::LOG_ID,
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ],
            'Log ID'
        );
        $table->addColumn(
            LogDataInterface::CREATED_AT,
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT,
            ],
            'Created at'
        );
        $table->addColumn(
            LogDataInterface::TYPE,
            Table::TYPE_TEXT,
            255,
            [
                'nullable' => false,
            ],
            'Type'
        );
        $table->addColumn(
            LogDataInterface::MESSAGE,
            Table::TYPE_TEXT,
            null,
            [
                'nullable' => false,
            ],
            'Message'
        );
        $table->addColumn(
            LogDataInterface::CONTEXT_DATA,
            Table::TYPE_TEXT,
            null,
            [
                'nullable' => false,
            ],
            'Context data'
        );
        $table->addIndex(
            $setup->getIdxName(
                LogData::MAIN_TABLE,
                [
                    LogDataInterface::TYPE,
                ],
                AdapterInterface::INDEX_TYPE_INDEX
            ),
            [
                LogDataInterface::TYPE,
            ],
            [
                'type' => AdapterInterface::INDEX_TYPE_INDEX,
            ]
        );

        $connection->createTable($table);
    }

    private function version101fixTimestampColumns(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $scheduleTable = $setup->getTable(Schedule::MAIN_TABLE);
        $connection->modifyColumn(
            $scheduleTable,
            ScheduleInterface::CREATED_AT,
            [
                'type' => Table::TYPE_TIMESTAMP,
                'default' => Table::TIMESTAMP_INIT,
            ]
        );

        $initialTable = $setup->getTable(Initial::MAIN_TABLE);
        $connection->modifyColumn(
            $initialTable,
            InitialInterface::CREATED_AT,
            [
                'type' => Table::TYPE_TIMESTAMP,
                'default' => Table::TIMESTAMP_INIT,
            ]
        );
    }

    private function version110addProcessedAtOnInitial(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $initialTable = $setup->getTable(Initial::MAIN_TABLE);
        $connection->addColumn(
            $initialTable,
            InitialInterface::PROCESSED_AT,
            [
                'size' => null,
                'type' => Table::TYPE_TIMESTAMP,
                'nullable' => false,
                'default' => '0000-00-00 00:00:00',
                'comment' => 'Processed at'
            ]
        );
    }

    private function version120addStatusOnInitial(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $initialTable = $setup->getTable(Initial::MAIN_TABLE);
        $connection->addColumn(
            $initialTable,
            InitialInterface::STATUS,
            [
                'size' => 1,
                'type' => Table::TYPE_SMALLINT,
                'nullable' => false,
                'comment' => 'Status'
            ]
        );
    }
}
