<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031140155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lease_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE housing_lease_type (housing_id UUID NOT NULL, lease_type_id INT NOT NULL, PRIMARY KEY(housing_id, lease_type_id))');
        $this->addSql('CREATE INDEX IDX_A2E41DB2AD5873E3 ON housing_lease_type (housing_id)');
        $this->addSql('CREATE INDEX IDX_A2E41DB2653A3F30 ON housing_lease_type (lease_type_id)');
        $this->addSql('COMMENT ON COLUMN housing_lease_type.housing_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE lease_type (id INT NOT NULL, label_fr VARCHAR(255) NOT NULL, label_en VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE housing_lease_type ADD CONSTRAINT FK_A2E41DB2AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_lease_type ADD CONSTRAINT FK_A2E41DB2653A3F30 FOREIGN KEY (lease_type_id) REFERENCES lease_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing ALTER apl_agreement DROP DEFAULT');

        $this->addSql('INSERT INTO lease_type (id,label_fr, label_en) VALUES (1,\'Meublé avec tacite reconduction\',\'furnished with automatic renewal\')');
        $this->addSql('INSERT INTO lease_type (id,label_fr, label_en) VALUES (2,\'Meublé sans tacite reconduction\',\'furnished without automatic renewal\')');
        $this->addSql('INSERT INTO lease_type (id,label_fr, label_en) VALUES (3,\'Non meublé avec tacite reconduction\',\'not furnished with automatic renewal\')');
        $this->addSql('INSERT INTO lease_type (id,label_fr, label_en) VALUES (4,\'Non meublé sans tacite reconducation\',\'not furnished and no automatic renewal\')');

        $this->addSql('ALTER SEQUENCE lease_type_id_seq RESTART WITH 5');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housing_lease_type DROP CONSTRAINT FK_A2E41DB2653A3F30');
        $this->addSql('DROP SEQUENCE lease_type_id_seq CASCADE');
        $this->addSql('DROP TABLE housing_lease_type');
        $this->addSql('DROP TABLE lease_type');
        $this->addSql('ALTER TABLE housing ALTER apl_agreement SET DEFAULT false');
    }
}
