<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170810115251 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organize_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, site VARCHAR(255) NOT NULL, phonenumber VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, create_date DATETIME NOT NULL, point POINT DEFAULT NULL COMMENT \'(DC2Type:point)\', outdoor VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, age LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', origin TIME DEFAULT NULL, duration VARCHAR(255) DEFAULT NULL, event_date DATETIME DEFAULT NULL, parking TINYINT(1) NOT NULL, food TINYINT(1) NOT NULL, tips VARCHAR(255) DEFAULT NULL, appointment TINYINT(1) NOT NULL, rating INT DEFAULT NULL, INDEX IDX_3BAE0AA7ACBC40A8 (organize_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eventIntermediate (event_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_81F0B25071F7E88B (event_id), INDEX IDX_81F0B25012469DE2 (category_id), PRIMARY KEY(event_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imageEvent (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_5570BF2671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentEvent (id INT AUTO_INCREMENT NOT NULL, father_id INT DEFAULT NULL, event_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_817CF7542055B9A2 (father_id), INDEX IDX_817CF75471F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organize (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE father (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7ACBC40A8 FOREIGN KEY (organize_id) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE eventIntermediate ADD CONSTRAINT FK_81F0B25071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eventIntermediate ADD CONSTRAINT FK_81F0B25012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imageEvent ADD CONSTRAINT FK_5570BF2671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE commentEvent ADD CONSTRAINT FK_817CF7542055B9A2 FOREIGN KEY (father_id) REFERENCES father (id)');
        $this->addSql('ALTER TABLE commentEvent ADD CONSTRAINT FK_817CF75471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eventIntermediate DROP FOREIGN KEY FK_81F0B25071F7E88B');
        $this->addSql('ALTER TABLE imageEvent DROP FOREIGN KEY FK_5570BF2671F7E88B');
        $this->addSql('ALTER TABLE commentEvent DROP FOREIGN KEY FK_817CF75471F7E88B');
        $this->addSql('ALTER TABLE eventIntermediate DROP FOREIGN KEY FK_81F0B25012469DE2');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7ACBC40A8');
        $this->addSql('ALTER TABLE commentEvent DROP FOREIGN KEY FK_817CF7542055B9A2');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE eventIntermediate');
        $this->addSql('DROP TABLE imageEvent');
        $this->addSql('DROP TABLE commentEvent');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE organize');
        $this->addSql('DROP TABLE father');
    }
}
