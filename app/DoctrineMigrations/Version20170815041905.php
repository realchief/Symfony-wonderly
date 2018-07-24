<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170815041905 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize ADD user_id INT DEFAULT NULL, ADD img VARCHAR(255) NOT NULL, ADD profession VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD location VARCHAR(255) NOT NULL, ADD age INT NOT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB957A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D24AB957A76ED395 ON organize (user_id)');
        $this->addSql('ALTER TABLE father ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE father ADD CONSTRAINT FK_CF2531B8A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF2531B8A76ED395 ON father (user_id)');
        $this->addSql('ALTER TABLE fos_user ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE father DROP FOREIGN KEY FK_CF2531B8A76ED395');
        $this->addSql('DROP INDEX UNIQ_CF2531B8A76ED395 ON father');
        $this->addSql('ALTER TABLE father DROP user_id');
        $this->addSql('ALTER TABLE fos_user DROP firstname, DROP lastname');
        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB957A76ED395');
        $this->addSql('DROP INDEX UNIQ_D24AB957A76ED395 ON organize');
        $this->addSql('ALTER TABLE organize DROP user_id, DROP img, DROP profession, DROP address, DROP location, DROP age');
    }
}
