<?php

namespace StageBit\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,
                            ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.1.4') < 0) {
            $installer
                ->getConnection()
                ->modifyColumn('stagebit_blogs', 'url_key', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 'length' => 255]);
        }

        $installer->endSetup();
    }
}