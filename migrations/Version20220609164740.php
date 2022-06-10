<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609164740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM school_emergency_qualification_question;');
        $this->addSql('ALTER TABLE school_emergency_qualification_question DROP CONSTRAINT fk_ea7a43671e27f6bf');
        $this->addSql('DROP SEQUENCE emergency_qualification_question_id_seq CASCADE');
        $this->addSql('DROP TABLE emergency_qualification_question');
        $this->addSql('DROP INDEX idx_ea7a43671e27f6bf');
        $this->addSql('ALTER TABLE school_emergency_qualification_question ADD question VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE school_emergency_qualification_question DROP question_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE emergency_qualification_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE emergency_qualification_question (id INT NOT NULL, translation_label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE school_emergency_qualification_question ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE school_emergency_qualification_question DROP question');
        $this->addSql('ALTER TABLE school_emergency_qualification_question ADD CONSTRAINT fk_ea7a43671e27f6bf FOREIGN KEY (question_id) REFERENCES emergency_qualification_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ea7a43671e27f6bf ON school_emergency_qualification_question (question_id)');
    }
}
