<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509125857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ADD school_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD last_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD first_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD birthdate DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD reduced_mobility BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD social_scholarship BOOLEAN DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN student.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B723AF33C32A47EE ON student (school_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF33C32A47EE');
        $this->addSql('DROP INDEX IDX_B723AF33C32A47EE');
        $this->addSql('ALTER TABLE student DROP school_id');
        $this->addSql('ALTER TABLE student DROP last_name');
        $this->addSql('ALTER TABLE student DROP first_name');
        $this->addSql('ALTER TABLE student DROP birthdate');
        $this->addSql('ALTER TABLE student DROP reduced_mobility');
        $this->addSql('ALTER TABLE student DROP social_scholarship');
    }
}
