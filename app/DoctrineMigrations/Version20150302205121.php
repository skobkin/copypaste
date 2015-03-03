<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * First migration. Creates new database in copypaste1 format or skipping creation if data already exists.
 */
class Version20150302205121 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $schemaManager = $this->connection->getSchemaManager();
        
        if ($schemaManager->tablesExist(['lang', 'paste'])) {
            $this->write('Copypaste1 tables detected. Skiping creation...');
        } else {
            $this->write('Creating database in old format...');
            
            $this->addSql(
                'CREATE TABLE `lang` (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT \'ID\',
                        `name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT \'Language title for GUI\',
                        `file` varchar(24) CHARACTER SET utf8 NOT NULL COMMENT \'Language filename for GeSHi\',
                        `enabled` tinyint(1) unsigned NOT NULL COMMENT \'Is language usable or not\',
                        PRIMARY KEY (`id`),
                        KEY `lang_enabled` (`enabled`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=190 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT=\'Programming languages\''
            );
            $this->addSql(
                'CREATE TABLE `paste` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT \'ID\',
                    `code` mediumtext CHARACTER SET utf8 NOT NULL COMMENT \'Code\',
                    `code_comment` text CHARACTER SET utf8 NOT NULL COMMENT \'Comments for code\',
                    `lang` int(11) unsigned NOT NULL COMMENT \'id языка ввода\',
                    `filename` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT \'Filename\',
                    `name` varchar(48) CHARACTER SET utf8 NOT NULL COMMENT \'Author of quote name\',
                    `date_pub` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'Publish date\',
                    `date_exp` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'Expire date\',
                    `ip` varchar(48) CHARACTER SET utf8 NOT NULL COMMENT \'IP\',
                    `secret` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT \'Paste secret\',
                    PRIMARY KEY (`id`),
                    KEY `expiration` (`date_exp`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1194 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
            );
        }
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE lang');
        $this->addSql('DROP TABLE paste');
    }
}
