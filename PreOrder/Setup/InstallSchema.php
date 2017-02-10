<?php

namespace Acidgreen\PreOrder\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * InstallSchema constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $connection = $installer->getConnection();

        $quoteItemTable = $installer->getTable('quote_item');
        if (!$connection->tableColumnExists($quoteItemTable, 'pre_order_note')) {
            $connection->addColumn($quoteItemTable, 'pre_order_note', [
                'TYPE' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'LENGTH' => 50,
                'NULLABLE' => false,
                'AFTER' => 'base_weee_tax_row_disposition',
                'COMMENT' => 'Quote Item'
            ]);
        }

        $orderItemTable = $installer->getTable('sales_order_item');
        if (!$connection->tableColumnExists($orderItemTable, 'pre_order_note')) {
            $connection->addColumn($orderItemTable, 'pre_order_note', [
                'TYPE' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'LENGTH' => 50,
                'NULLABLE' => false,
                'AFTER' => 'base_weee_tax_row_disposition',
                'COMMENT' => 'Sales Order Item'
            ]);
        }

        $invoiceItemTable = $installer->getTable('sales_invoice_item');
        if (!$connection->tableColumnExists($invoiceItemTable, 'pre_order_note')) {
            $connection->addColumn($invoiceItemTable, 'pre_order_note', [
                'TYPE' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'LENGTH' => 50,
                'NULLABLE' => false,
                'AFTER' => 'base_weee_tax_row_disposition',
                'COMMENT' => 'Sales Invoice Item'
            ]);
        }

        $installer->endSetup();
    }
}
