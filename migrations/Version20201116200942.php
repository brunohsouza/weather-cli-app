<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116200942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE weather_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE weather (id INT NOT NULL, main VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, temp DOUBLE PRECISION NOT NULL, feels_like DOUBLE PRECISION NOT NULL, temp_min DOUBLE PRECISION NOT NULL, temp_max DOUBLE PRECISION NOT NULL, wind_speed DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE weather_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE weather');
    }
}
