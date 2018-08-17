<?php

namespace Appsgenii\Blog\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @package Appsgenii\Blog\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install Blog Posts table
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('Appsgenii_gifts');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'post_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Title'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Email'
                )
                ->addColumn(
                    'contact',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Contact'
                )
                ->addColumn(
                    'company',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Company'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Message'
                )
                ->addColumn(
                    'files',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Multiple Files'
                )
                ->addColumn(
                    'creation_time',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Creation Time'
                )
                ->addColumn('update_time',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Update Time'
                )
                ->setComment('Appsgenii Gifts');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}