<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116205145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE temperature ADD humidity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE weather DROP temp');
        $this->addSql('ALTER TABLE weather DROP feels_like');
        $this->addSql('ALTER TABLE weather DROP temp_min');
        $this->addSql('ALTER TABLE weather DROP temp_max');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE weather ADD temp DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weather ADD feels_like DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weather ADD temp_min DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weather ADD temp_max DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE temperature DROP humidity');
    }
}
