<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607093032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE housing_equipment (housing_id UUID NOT NULL, equipment_id INT NOT NULL, PRIMARY KEY(housing_id, equipment_id))');
        $this->addSql('CREATE INDEX IDX_4A001DAAD5873E3 ON housing_equipment (housing_id)');
        $this->addSql('CREATE INDEX IDX_4A001DA517FE9FE ON housing_equipment (equipment_id)');
        $this->addSql('COMMENT ON COLUMN housing_equipment.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE housing_equipment ADD CONSTRAINT FK_4A001DAAD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_equipment ADD CONSTRAINT FK_4A001DA517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE housing_equipment');
    }
}
