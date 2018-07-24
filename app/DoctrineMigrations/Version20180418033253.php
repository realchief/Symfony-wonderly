<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180418033253 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP parking, DROP appointment, DROP dog_friendly, CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE outdoor outdoor VARCHAR(255) DEFAULT NULL, CHANGE food food TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event ADD parking TINYINT(1) NOT NULL, ADD appointment VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD dog_friendly TINYINT(1) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE outdoor outdoor VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE food food TINYINT(1) NOT NULL');
    }
}
