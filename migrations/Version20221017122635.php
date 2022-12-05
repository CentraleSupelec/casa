<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017122635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE guarantor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE guarantor (id INT NOT NULL, label_fr VARCHAR(255) NOT NULL, label_en VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE housing_group_guarantor (housing_group_id UUID NOT NULL, guarantor_id INT NOT NULL, PRIMARY KEY(housing_group_id, guarantor_id))');
        $this->addSql('CREATE INDEX IDX_46D8C32C6B526EA6 ON housing_group_guarantor (housing_group_id)');
        $this->addSql('CREATE INDEX IDX_46D8C32C5C3575A7 ON housing_group_guarantor (guarantor_id)');
        $this->addSql('COMMENT ON COLUMN housing_group_guarantor.housing_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE housing_group_guarantor ADD CONSTRAINT FK_46D8C32C6B526EA6 FOREIGN KEY (housing_group_id) REFERENCES housing_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_group_guarantor ADD CONSTRAINT FK_46D8C32C5C3575A7 FOREIGN KEY (guarantor_id) REFERENCES guarantor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('INSERT INTO guarantor (id,label_fr, label_en) VALUES (1,\'Pas d exigence particulière\',\'No special requirement\')');
        $this->addSql('INSERT INTO guarantor (id,label_fr, label_en) VALUES (2,\'Garant français\',\'French guarantor\')');
        $this->addSql('INSERT INTO guarantor (id,label_fr, label_en) VALUES (3,\'Garant en France\',\'Guarantor in France\')');
        $this->addSql('INSERT INTO guarantor (id,label_fr, label_en) VALUES (4,\'Garant ayan un compte bancaire en France\',\'Guarantor with a french bank account\')');

        $this->addSql('ALTER SEQUENCE guarantor_id_seq RESTART WITH 5');

        // For every existing housing Group, the 1st as default ( client requirement )
        $this->addSql('INSERT INTO housing_group_guarantor (housing_group_id, guarantor_id) SELECT housing_group.id, 1 from housing_group');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE housing_group_guarantor DROP CONSTRAINT FK_46D8C32C5C3575A7');
        $this->addSql('DROP SEQUENCE guarantor_id_seq CASCADE');
        $this->addSql('DROP TABLE guarantor');
        $this->addSql('DROP TABLE housing_group_guarantor');
    }
}
