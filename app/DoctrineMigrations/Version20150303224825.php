<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Set secret as NULL where secret = ''
 */
class Version20150303224825 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE copypastes SET secret=NULL WHERE secret=\'\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE copypastes SET secret=\'\' WHERE secret=NULL');
    }
}
