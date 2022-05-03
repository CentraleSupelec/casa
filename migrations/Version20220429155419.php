<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429155419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE housing_group_school (housing_group_id UUID NOT NULL, school_id UUID NOT NULL, PRIMARY KEY(housing_group_id, school_id))');
        $this->addSql('CREATE INDEX IDX_52F50CEE6B526EA6 ON housing_group_school (housing_group_id)');
        $this->addSql('CREATE INDEX IDX_52F50CEEC32A47EE ON housing_group_school (school_id)');
        $this->addSql('COMMENT ON COLUMN housing_group_school.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN housing_group_school.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE school (id UUID NOT NULL, name VARCHAR(255) NOT NULL, id_government VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address_country VARCHAR(50) NOT NULL, address_postal_code VARCHAR(10) NOT NULL, address_city VARCHAR(80) NOT NULL, address_street VARCHAR(255) NOT NULL, address_street_detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN school.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE housing_group_school ADD CONSTRAINT FK_52F50CEE6B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_school ADD CONSTRAINT FK_52F50CEEC32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE housing_group_school DROP CONSTRAINT FK_52F50CEEC32A47EE');
        $this->addSql('DROP TABLE housing_group_school');
        $this->addSql('DROP TABLE school');
    }
}
