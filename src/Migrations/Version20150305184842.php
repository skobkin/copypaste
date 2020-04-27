<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150305184842 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE copypastes SET date_expire=NULL WHERE date_expire=\'2020-01-01 00:00:00\'');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE copypastes SET date_expire=\'2020-01-01 00:00:00\' WHERE date_expire=NULL');
    }
}
