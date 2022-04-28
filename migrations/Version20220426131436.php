<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426131436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_housing (student_id UUID NOT NULL, housing_id INT NOT NULL, PRIMARY KEY(student_id, housing_id))');
        $this->addSql('CREATE INDEX IDX_16B7F73BCB944F1A ON student_housing (student_id)');
        $this->addSql('CREATE INDEX IDX_16B7F73BAD5873E3 ON student_housing (housing_id)');
        $this->addSql('COMMENT ON COLUMN student_housing.student_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE student_housing ADD CONSTRAINT FK_16B7F73BCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_housing ADD CONSTRAINT FK_16B7F73BAD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE student_housing');
    }
}
