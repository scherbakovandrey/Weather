<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190201090227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_2D5B0234F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(2) NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_5373C96677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');

        $this->addSql('INSERT INTO country (id, code, name) 
                      VALUES
                      (1, "DE", "Germany"), 
                      (2, "ES", "Spain"), 
                      (3, "AT", "Austria"), 
                      (4, "PL", "Poland"), 
                      (5, "NL", "Netherlands"), 
                      (6, "UK", "United Kingdom")
                      ');

        $this->addSql('INSERT INTO city (id, country_id, name) 
                      VALUES
                      (1, 1, "Berlin"), 
                      (2, 1, "Dusseldorf"), 
                      (3, 2, "Madrid"), 
                      (4, 3, "Vienna"), 
                      (5, 4, "Warsaw"), 
                      (6, 5, "Amsterdam"),
                      (7, 6, "London")
                      ');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F92F3E70');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE country');
    }
}
