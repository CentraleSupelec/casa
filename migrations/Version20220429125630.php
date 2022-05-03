<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429125630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE equipment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipment (id INT NOT NULL, label VARCHAR(80) NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE housing (id UUID NOT NULL, housing_group_id UUID NOT NULL, description VARCHAR(512) DEFAULT NULL, redirect_link VARCHAR(255) DEFAULT NULL, available BOOLEAN NOT NULL, type VARCHAR(50) NOT NULL, area_min INT NOT NULL, area_max INT DEFAULT NULL, rent_min INT NOT NULL, rent_max INT DEFAULT NULL, charges_min INT DEFAULT NULL, charges_max INT DEFAULT NULL, charges_included BOOLEAN NOT NULL, application_fee_min INT NOT NULL, application_fee_max INT DEFAULT NULL, security_deposit_min INT NOT NULL, security_deposit_max INT DEFAULT NULL, living_mode VARCHAR(80) DEFAULT NULL, occupation_mode VARCHAR(80) DEFAULT NULL, accessibility BOOLEAN NOT NULL, smoking BOOLEAN NOT NULL, animals_allowed BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB8142C36B526EA6 ON housing (housing_group_id)');
        $this->addSql('COMMENT ON COLUMN housing.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN housing.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE housing_group (id UUID NOT NULL, lessor_id UUID NOT NULL, name VARCHAR(80) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address_country VARCHAR(50) NOT NULL, address_postal_code VARCHAR(10) NOT NULL, address_city VARCHAR(80) NOT NULL, address_street VARCHAR(255) NOT NULL, address_street_detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEC38205D737E9B1 ON housing_group (lessor_id)');
        $this->addSql('COMMENT ON COLUMN housing_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN housing_group.lessor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE housing_group_equipment (housing_group_id UUID NOT NULL, equipment_id INT NOT NULL, PRIMARY KEY(housing_group_id, equipment_id))');
        $this->addSql('CREATE INDEX IDX_B44DE33A6B526EA6 ON housing_group_equipment (housing_group_id)');
        $this->addSql('CREATE INDEX IDX_B44DE33A517FE9FE ON housing_group_equipment (equipment_id)');
        $this->addSql('COMMENT ON COLUMN housing_group_equipment.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE housing_group_service (id UUID NOT NULL, service_id INT NOT NULL, housing_group_id UUID NOT NULL, is_optional BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA37547FED5CA9E6 ON housing_group_service (service_id)');
        $this->addSql('CREATE INDEX IDX_FA37547F6B526EA6 ON housing_group_service (housing_group_id)');
        $this->addSql('COMMENT ON COLUMN housing_group_service.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN housing_group_service.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE housing_picture (id UUID NOT NULL, housing_id UUID NOT NULL, label VARCHAR(80) DEFAULT NULL, picture VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AF262C41AD5873E3 ON housing_picture (housing_id)');
        $this->addSql('COMMENT ON COLUMN housing_picture.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN housing_picture.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE lessor (id UUID NOT NULL, name VARCHAR(80) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN lessor.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE point_of_interest (id UUID NOT NULL, housing_group_id UUID NOT NULL, category VARCHAR(80) NOT NULL, label VARCHAR(80) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E67AD3596B526EA6 ON point_of_interest (housing_group_id)');
        $this->addSql('COMMENT ON COLUMN point_of_interest.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN point_of_interest.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, label VARCHAR(80) NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE student (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, last_login_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, verified BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33E7927C74 ON student (email)');
        $this->addSql('COMMENT ON COLUMN student.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE student_housing (student_id UUID NOT NULL, housing_id UUID NOT NULL, PRIMARY KEY(student_id, housing_id))');
        $this->addSql('CREATE INDEX IDX_16B7F73BCB944F1A ON student_housing (student_id)');
        $this->addSql('CREATE INDEX IDX_16B7F73BAD5873E3 ON student_housing (housing_id)');
        $this->addSql('COMMENT ON COLUMN student_housing.student_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN student_housing.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C36B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group ADD CONSTRAINT FK_BEC38205D737E9B1 FOREIGN KEY (lessor_id) REFERENCES lessor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_equipment ADD CONSTRAINT FK_B44DE33A6B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_equipment ADD CONSTRAINT FK_B44DE33A517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_service ADD CONSTRAINT FK_FA37547FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_service ADD CONSTRAINT FK_FA37547F6B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_picture ADD CONSTRAINT FK_AF262C41AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE point_of_interest ADD CONSTRAINT FK_E67AD3596B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_housing ADD CONSTRAINT FK_16B7F73BCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_housing ADD CONSTRAINT FK_16B7F73BAD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE housing_group_equipment DROP CONSTRAINT FK_B44DE33A517FE9FE');
        $this->addSql('ALTER TABLE housing_picture DROP CONSTRAINT FK_AF262C41AD5873E3');
        $this->addSql('ALTER TABLE student_housing DROP CONSTRAINT FK_16B7F73BAD5873E3');
        $this->addSql('ALTER TABLE housing DROP CONSTRAINT FK_FB8142C36B526EA6');
        $this->addSql('ALTER TABLE housing_group_equipment DROP CONSTRAINT FK_B44DE33A6B526EA6');
        $this->addSql('ALTER TABLE housing_group_service DROP CONSTRAINT FK_FA37547F6B526EA6');
        $this->addSql('ALTER TABLE point_of_interest DROP CONSTRAINT FK_E67AD3596B526EA6');
        $this->addSql('ALTER TABLE housing_group DROP CONSTRAINT FK_BEC38205D737E9B1');
        $this->addSql('ALTER TABLE housing_group_service DROP CONSTRAINT FK_FA37547FED5CA9E6');
        $this->addSql('ALTER TABLE student_housing DROP CONSTRAINT FK_16B7F73BCB944F1A');
        $this->addSql('DROP SEQUENCE equipment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE housing');
        $this->addSql('DROP TABLE housing_group');
        $this->addSql('DROP TABLE housing_group_equipment');
        $this->addSql('DROP TABLE housing_group_service');
        $this->addSql('DROP TABLE housing_picture');
        $this->addSql('DROP TABLE lessor');
        $this->addSql('DROP TABLE point_of_interest');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_housing');
    }
}
