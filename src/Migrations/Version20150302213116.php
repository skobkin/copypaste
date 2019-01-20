<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Updating tables and column names
 */
class Version20150302213116 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // Tables structure
        $this->write('Upgrading column names and setting...');
        
        $this->addSql(
            'ALTER TABLE lang
                CHANGE id id INT AUTO_INCREMENT NOT NULL,
                CHANGE name name VARCHAR(100) NOT NULL,
                CHANGE file code VARCHAR(24) NOT NULL,
                CHANGE enabled enabled TINYINT(1) NOT NULL'
        );
        $this->addSql('CREATE INDEX idx_code ON lang (code)');
        
        $this->addSql(
            'ALTER TABLE paste
                CHANGE id id INT AUTO_INCREMENT NOT NULL,
                CHANGE code text LONGTEXT NOT NULL,
                CHANGE code_comment description LONGTEXT DEFAULT NULL,
                CHANGE lang language INT NOT NULL,
                CHANGE filename file_name VARCHAR(128) DEFAULT NULL,
                CHANGE name author VARCHAR(48) DEFAULT NULL,
                CHANGE date_pub date_publish DATETIME NOT NULL,
                CHANGE date_exp date_expire DATETIME DEFAULT NULL,
                CHANGE ip ip VARCHAR(48) NOT NULL,
                CHANGE secret secret VARCHAR(16) DEFAULT NULL'
        );
        
        // Renaming tables
        $this->write('Renaming tables to the full name format...');
        $this->addSql('RENAME TABLE lang TO languages');
        $this->addSql('RENAME TABLE paste TO copypastes');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // Tables structure
        $this->write('Downgrading column names and setting...');
        
        $this->addSql('DROP INDEX idx_code ON languages');
        $this->addSql(
            'ALTER TABLE lang
                CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'ID\',
                CHANGE name name VARCHAR(100) NOT NULL,
                CHANGE code file VARCHAR(24) NOT NULL,
                CHANGE enabled enabled TINYINT(1) NOT NULL COMMENT \'Is language usable\''
        );
        
        $this->addSql(
            'ALTER TABLE paste
                CHANGE id id INT AUTO_INCREMENT NOT NULL,
                CHANGE text code LONGTEXT NOT NULL COMMENT \'Code\',
                CHANGE description code_comment LONGTEXT NOT NULL COMMENT \'Comments for code\',
                CHANGE language lang INT UNSIGNED NOT NULL COMMENT \'Language ID\',
                CHANGE file_name filename VARCHAR(128) NOT NULL COMMENT \'Filename\',
                CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'ID\',
                CHANGE author name VARCHAR(48) NOT NULL COMMENT \'Author of quote name\',
                CHANGE date_publish date_pub DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'Publish date\',
                CHANGE date_expire date_exp DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'Expire date\',
                CHANGE ip ip varchar(48) NOT NULL COMMENT \'IP\',
                CHANGE secret secret VARCHAR(16) NOT NULL COMMENT \'Paste secret\''
        );
        
        // Renaming tables
        $this->write('Renaming tables to the old short format...');
        $this->addSql('RENAME TABLE languages TO lang');
        $this->addSql('RENAME TABLE paste TO copypastes');
    }
}
