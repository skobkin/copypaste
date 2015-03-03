<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150305184842 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE copypastes SET date_expire=NULL WHERE date_expire=\'0000-00-00 00:00:00\'');

    }

    public function down(Schema $schema)
    {
        $this->addSql('UPDATE copypastes SET date_expire=\'0000-00-00 00:00:00\' WHERE date_expire=NULL');
    }
}
