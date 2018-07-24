<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170918043121 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fatherIntermediate (father_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_21C86CE52055B9A2 (father_id), INDEX IDX_21C86CE512469DE2 (category_id), PRIMARY KEY(father_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fatherIntermediate ADD CONSTRAINT FK_21C86CE52055B9A2 FOREIGN KEY (father_id) REFERENCES father (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fatherIntermediate ADD CONSTRAINT FK_21C86CE512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE father ADD age INT NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD location VARCHAR(255) NOT NULL, ADD img VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fatherIntermediate');
        $this->addSql('ALTER TABLE father DROP age, DROP address, DROP location, DROP img');
    }
}
