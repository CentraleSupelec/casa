<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220603160336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE housing_group_school');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE housing_group_school (housing_group_id UUID NOT NULL, school_id UUID NOT NULL, PRIMARY KEY(housing_group_id, school_id))');
        $this->addSql('CREATE INDEX idx_52f50cee6b526ea6 ON housing_group_school (housing_group_id)');
        $this->addSql('CREATE INDEX idx_52f50ceec32a47ee ON housing_group_school (school_id)');
        $this->addSql('COMMENT ON COLUMN housing_group_school.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN housing_group_school.school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE housing_group_school ADD CONSTRAINT fk_52f50cee6b526ea6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_school ADD CONSTRAINT fk_52f50ceec32a47ee FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
