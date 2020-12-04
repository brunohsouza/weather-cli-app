<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116201111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weather ADD city_id INT NOT NULL');
        $this->addSql('ALTER TABLE weather ADD CONSTRAINT FK_4CD0D36E8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CD0D36E8BAC62AF ON weather (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE weather DROP CONSTRAINT FK_4CD0D36E8BAC62AF');
        $this->addSql('DROP INDEX UNIQ_4CD0D36E8BAC62AF');
        $this->addSql('ALTER TABLE weather DROP city_id');
    }
}
