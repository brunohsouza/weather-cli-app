<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201115183322 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE coordinates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, coordinates JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D5B0234F92F3E70 ON city (country_id)');
        $this->addSql('CREATE TABLE coordinates (id INT NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, name VARCHAR(255) NOT NULL, acronyms VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE city DROP CONSTRAINT FK_2D5B0234F92F3E70');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE coordinates_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE country_id_seq CASCADE');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE coordinates');
        $this->addSql('DROP TABLE country');
    }
}
