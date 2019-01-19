<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Converting languages to the InnoDB and adding foreign key
 */
class Version20150302223210 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->write('Converting languages table to InnoDB format...');
        $this->addSql('ALTER TABLE languages ENGINE=InnoDB');
        $this->write('Updating copypaste table structure...');
        $this->addSql('ALTER TABLE copypastes CHANGE language language_id INT DEFAULT NULL');
        $this->write('Adding foreign key...');
        $this->addSql('ALTER TABLE copypastes ADD CONSTRAINT FK_DBA4BEBE82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id)');
        $this->write('Creating copypaste language index...');
        $this->addSql('CREATE INDEX IDX_DBA4BEBE82F1BAF4 ON copypastes (language_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->write('Dropping foreign key...');
        $this->addSql('ALTER TABLE copypastes DROP FOREIGN KEY FK_DBA4BEBE82F1BAF4');
        $this->write('Dropping copypaste language index...');
        $this->addSql('DROP INDEX IDX_DBA4BEBE82F1BAF4 ON copypastes');
        $this->addSql('ALTER TABLE copypastes CHANGE language_id language INT NOT NULL, DROP language_id');
        $this->write('Converting languages table to MyISAM format...');
        $this->addSql('ALTER TABLE languages ENGINE=MyISAM');
    }
}
