<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018071343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE occupation_mode_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE housing_occupation_mode (housing_id UUID NOT NULL, occupation_mode_id INT NOT NULL, PRIMARY KEY(housing_id, occupation_mode_id))');
        $this->addSql('CREATE INDEX IDX_78EE8338AD5873E3 ON housing_occupation_mode (housing_id)');
        $this->addSql('CREATE INDEX IDX_78EE8338949FEE90 ON housing_occupation_mode (occupation_mode_id)');
        $this->addSql('COMMENT ON COLUMN housing_occupation_mode.housing_id IS \'(DC2Type:uuid)\'');

        $this->addSql('CREATE TABLE occupation_mode (id INT NOT NULL, label_fr VARCHAR(255) NOT NULL, label_en VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE housing_occupation_mode ADD CONSTRAINT FK_78EE8338AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_occupation_mode ADD CONSTRAINT FK_78EE8338949FEE90 FOREIGN KEY (occupation_mode_id) REFERENCES occupation_mode (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        // New values

        $this->addSql('INSERT INTO occupation_mode (id,label_fr, label_en) VALUES (1,\'à ocuper seul\',\'alone\')');
        $this->addSql('INSERT INTO occupation_mode (id,label_fr, label_en) VALUES (2,\'En couple sans enfant\',\'couple no children\')');
        $this->addSql('INSERT INTO occupation_mode (id,label_fr, label_en) VALUES (3,\'Famille monoparentale\',\'\')');
        $this->addSql('INSERT INTO occupation_mode (id,label_fr, label_en) VALUES (4,\'En couple avec enfant\',\'couple with children\')');
        $this->addSql('INSERT INTO occupation_mode (id,label_fr, label_en) VALUES (5,\'Colocation\',\'Share\')');
        $this->addSql('INSERT INTO occupation_mode (id,label_fr, label_en) VALUES (6,\'Colocation non mixte\',\'Share ..?\')');

        $this->addSql('ALTER SEQUENCE occupation_mode_id_seq RESTART WITH 7');

        // Migrate old occupation mode ( constants )

        // 'housing.occupation_mode.alone' => 'alone',
        $this->addSql('INSERT INTO housing_occupation_mode (housing_id, occupation_mode_id) SELECT housing.id, 1 from housing where housing.occupation_mode = \'alone\'');
        // 'housing.occupation_mode.couple' => 'couple',
        // choice to map to 2
        $this->addSql('INSERT INTO housing_occupation_mode (housing_id, occupation_mode_id) SELECT housing.id, 2 from housing where housing.occupation_mode = \'couple\'');
        // 'housing.occupation_mode.share' => 'share',
        $this->addSql('INSERT INTO housing_occupation_mode (housing_id, occupation_mode_id) SELECT housing.id, 5 from housing where housing.occupation_mode = \'share\'');

        // Drop Old column
        $this->addSql('ALTER TABLE housing DROP occupation_mode');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE housing_occupation_mode DROP CONSTRAINT FK_78EE8338949FEE90');
        $this->addSql('DROP SEQUENCE occupation_mode_id_seq CASCADE');
        $this->addSql('DROP TABLE housing_occupation_mode');
        $this->addSql('DROP TABLE occupation_mode');

        $this->addSql('ALTER TABLE housing ADD occupation_mode VARCHAR(80) NOT NULL');
    }
}
