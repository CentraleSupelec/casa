<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516161508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE school_criterion (id UUID NOT NULL, housing_id UUID NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCDE1CCAD5873E3 ON school_criterion (housing_id)');
        $this->addSql('COMMENT ON COLUMN school_criterion.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school_criterion.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE school_criterion_school (school_criterion_id UUID NOT NULL, school_id UUID NOT NULL, PRIMARY KEY(school_criterion_id, school_id))');
        $this->addSql('CREATE INDEX IDX_35E7F4575C44D6B9 ON school_criterion_school (school_criterion_id)');
        $this->addSql('CREATE INDEX IDX_35E7F457C32A47EE ON school_criterion_school (school_id)');
        $this->addSql('COMMENT ON COLUMN school_criterion_school.school_criterion_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school_criterion_school.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE social_scholarship_criterion (id UUID NOT NULL, housing_id UUID NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_326BB551AD5873E3 ON social_scholarship_criterion (housing_id)');
        $this->addSql('COMMENT ON COLUMN social_scholarship_criterion.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN social_scholarship_criterion.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE school_criterion ADD CONSTRAINT FK_DCDE1CCAD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE school_criterion_school ADD CONSTRAINT FK_35E7F4575C44D6B9 FOREIGN KEY (school_criterion_id) REFERENCES school_criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE school_criterion_school ADD CONSTRAINT FK_35E7F457C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE social_scholarship_criterion ADD CONSTRAINT FK_326BB551AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE school_criterion_school DROP CONSTRAINT FK_35E7F4575C44D6B9');
        $this->addSql('DROP TABLE school_criterion');
        $this->addSql('DROP TABLE school_criterion_school');
        $this->addSql('DROP TABLE social_scholarship_criterion');
    }
}
