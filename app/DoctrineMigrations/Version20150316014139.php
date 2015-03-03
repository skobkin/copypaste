<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Copypastes table changes:
 * - enabled -> is_enabled
 * - is_preferred added
 */
class Version20150316014139 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE languages CHANGE enabled is_enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE languages ADD is_preferred TINYINT(1) NOT NULL');
        $this->addSql('CREATE INDEX idx_preferred ON languages (is_preferred)');
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE languages CHANGE is_enabled enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE languages DROP is_preferred');
        $this->addSql('DROP INDEX idx_preferred ON languages');
    }
}
