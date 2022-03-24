<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322164329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE housing_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE housing_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE housing_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lessor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE housing (id INT NOT NULL, housing_group_id INT NOT NULL, description VARCHAR(512) DEFAULT NULL, redirect_link VARCHAR(255) DEFAULT NULL, available BOOLEAN NOT NULL, type VARCHAR(50) NOT NULL, area_min INT NOT NULL, area_max INT DEFAULT NULL, rent_min INT NOT NULL, rent_max INT DEFAULT NULL, charges_min INT DEFAULT NULL, charges_max INT DEFAULT NULL, charges_included BOOLEAN NOT NULL, application_fee_min INT NOT NULL, application_fee_max INT DEFAULT NULL, security_deposit_min INT NOT NULL, security_deposit_max INT DEFAULT NULL, living_mode VARCHAR(80) DEFAULT NULL, occupation_mode VARCHAR(80) DEFAULT NULL, accessibility BOOLEAN NOT NULL, smoking BOOLEAN NOT NULL, animals_allowed BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB8142C36B526EA6 ON housing (housing_group_id)');
        $this->addSql('CREATE TABLE housing_group (id INT NOT NULL, lessor_id INT NOT NULL, name VARCHAR(80) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address_country VARCHAR(50) NOT NULL, address_postal_code VARCHAR(10) NOT NULL, address_city VARCHAR(80) NOT NULL, address_street VARCHAR(255) NOT NULL, address_street_detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEC38205D737E9B1 ON housing_group (lessor_id)');
        $this->addSql('CREATE TABLE housing_picture (id INT NOT NULL, housing_id INT NOT NULL, label VARCHAR(80) DEFAULT NULL, picture VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AF262C41AD5873E3 ON housing_picture (housing_id)');
        $this->addSql('CREATE TABLE lessor (id INT NOT NULL, name VARCHAR(80) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C36B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group ADD CONSTRAINT FK_BEC38205D737E9B1 FOREIGN KEY (lessor_id) REFERENCES lessor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_picture ADD CONSTRAINT FK_AF262C41AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE housing_picture DROP CONSTRAINT FK_AF262C41AD5873E3');
        $this->addSql('ALTER TABLE housing DROP CONSTRAINT FK_FB8142C36B526EA6');
        $this->addSql('ALTER TABLE housing_group DROP CONSTRAINT FK_BEC38205D737E9B1');
        $this->addSql('DROP SEQUENCE housing_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE housing_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE housing_picture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lessor_id_seq CASCADE');
        $this->addSql('DROP TABLE housing');
        $this->addSql('DROP TABLE housing_group');
        $this->addSql('DROP TABLE housing_picture');
        $this->addSql('DROP TABLE lessor');
    }
}
