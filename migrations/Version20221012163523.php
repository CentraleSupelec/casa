<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221012163523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stay_duration_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE housing_stay_duration (housing_id UUID NOT NULL, stay_duration_id INT NOT NULL, PRIMARY KEY(housing_id, stay_duration_id))');
        $this->addSql('CREATE INDEX IDX_673C4046AD5873E3 ON housing_stay_duration (housing_id)');
        $this->addSql('CREATE INDEX IDX_673C4046A857180B ON housing_stay_duration (stay_duration_id)');
        $this->addSql('COMMENT ON COLUMN housing_stay_duration.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE stay_duration (id INT NOT NULL, label_fr VARCHAR(255) NOT NULL, label_en VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE housing_stay_duration ADD CONSTRAINT FK_673C4046AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_stay_duration ADD CONSTRAINT FK_673C4046A857180B FOREIGN KEY (stay_duration_id) REFERENCES stay_duration (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('INSERT INTO stay_duration (id,label_fr, label_en) VALUES (1,\'Long séjour : supérieur à 1 an\',\'Long term lease : more than one year\')');
        $this->addSql('INSERT INTO stay_duration (id,label_fr, label_en) VALUES (2,\'Moyen séjour : entre 6 mois et 1 an\',\'Mid term lease : between 6 months and 1 year\')');
        $this->addSql('INSERT INTO stay_duration (id,label_fr, label_en) VALUES (3,\'Court séjour : entre 1 mois et 6 mois\',\'Short term lease : between 1 and 6 months\')');
        $this->addSql('INSERT INTO stay_duration (id,label_fr, label_en) VALUES (4,\'Séjour temporaire : inférieur à 1 mois\',\'Temporay lease : less than 1 month\')');

        $this->addSql('ALTER SEQUENCE stay_duration_id_seq RESTART WITH 5');

        // For every existing housing, set Long term as default existing value ( client requirement )
        $this->addSql('INSERT INTO housing_stay_duration (housing_id, stay_duration_id) SELECT housing.id, 1 from housing');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housing_stay_duration DROP CONSTRAINT FK_673C4046A857180B');
        $this->addSql('DROP SEQUENCE stay_duration_id_seq CASCADE');
        $this->addSql('DROP TABLE housing_stay_duration');
        $this->addSql('DROP TABLE stay_duration');
    }
}
