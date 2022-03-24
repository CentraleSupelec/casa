<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220323145221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE equipment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE housing_group_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE point_of_interest_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipment (id INT NOT NULL, label VARCHAR(80) NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE housing_group_equipment (housing_group_id INT NOT NULL, equipment_id INT NOT NULL, PRIMARY KEY(housing_group_id, equipment_id))');
        $this->addSql('CREATE INDEX IDX_B44DE33A6B526EA6 ON housing_group_equipment (housing_group_id)');
        $this->addSql('CREATE INDEX IDX_B44DE33A517FE9FE ON housing_group_equipment (equipment_id)');
        $this->addSql('CREATE TABLE housing_group_service (id INT NOT NULL, service_id INT NOT NULL, housing_group_id INT NOT NULL, is_optional BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA37547FED5CA9E6 ON housing_group_service (service_id)');
        $this->addSql('CREATE INDEX IDX_FA37547F6B526EA6 ON housing_group_service (housing_group_id)');
        $this->addSql('CREATE TABLE point_of_interest (id INT NOT NULL, housing_group_id INT NOT NULL, category VARCHAR(80) NOT NULL, label VARCHAR(80) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E67AD3596B526EA6 ON point_of_interest (housing_group_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, label VARCHAR(80) NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE housing_group_equipment ADD CONSTRAINT FK_B44DE33A6B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_equipment ADD CONSTRAINT FK_B44DE33A517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_service ADD CONSTRAINT FK_FA37547FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_service ADD CONSTRAINT FK_FA37547F6B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE point_of_interest ADD CONSTRAINT FK_E67AD3596B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE housing_group_equipment DROP CONSTRAINT FK_B44DE33A517FE9FE');
        $this->addSql('ALTER TABLE housing_group_service DROP CONSTRAINT FK_FA37547FED5CA9E6');
        $this->addSql('DROP SEQUENCE equipment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE housing_group_service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE point_of_interest_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE housing_group_equipment');
        $this->addSql('DROP TABLE housing_group_service');
        $this->addSql('DROP TABLE point_of_interest');
        $this->addSql('DROP TABLE service');
    }
}
