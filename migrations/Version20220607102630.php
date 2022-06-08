<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607102630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE emergency_qualification_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE emergency_qualification_question (id INT NOT NULL, translation_label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE school_emergency_qualification_question (id UUID NOT NULL, question_id INT NOT NULL, school_id UUID NOT NULL, contact_list TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA7A43671E27F6BF ON school_emergency_qualification_question (question_id)');
        $this->addSql('CREATE INDEX IDX_EA7A4367C32A47EE ON school_emergency_qualification_question (school_id)');
        $this->addSql('COMMENT ON COLUMN school_emergency_qualification_question.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school_emergency_qualification_question.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN school_emergency_qualification_question.contact_list IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE school_emergency_qualification_question ADD CONSTRAINT FK_EA7A43671E27F6BF FOREIGN KEY (question_id) REFERENCES emergency_qualification_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE school_emergency_qualification_question ADD CONSTRAINT FK_EA7A4367C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE school_emergency_qualification_question DROP CONSTRAINT FK_EA7A43671E27F6BF');
        $this->addSql('DROP SEQUENCE emergency_qualification_question_id_seq CASCADE');
        $this->addSql('DROP TABLE emergency_qualification_question');
        $this->addSql('DROP TABLE school_emergency_qualification_question');
    }
}
