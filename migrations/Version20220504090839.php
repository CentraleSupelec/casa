<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504090839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housing_group ADD address_coordinates_latitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE housing_group ADD address_coordinates_longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE school ADD address_coordinates_latitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE school ADD address_coordinates_longitude DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housing_group DROP address_coordinates_latitude');
        $this->addSql('ALTER TABLE housing_group DROP address_coordinates_longitude');
        $this->addSql('ALTER TABLE school DROP address_coordinates_latitude');
        $this->addSql('ALTER TABLE school DROP address_coordinates_longitude');
    }
}
