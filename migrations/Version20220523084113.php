<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220523084113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parent_school (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN parent_school.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE school ADD parent_school_id UUID');

        $count = $this->connection->fetchFirstColumn('SELECT count(*) FROM school');

        if ($count[0] > 0) {
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'École normale supérieure Paris-Saclay']);
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'Université d\'Évry']);
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'Université de Versailles Saint-Quentin-en-Yvelines']);
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'Institut d\'Optique Graduate School']);
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'CentraleSupélec']);
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'AgroParisTech']);
            $this->addSql('INSERT INTO parent_school (id,name) values(:id,:name)', [new UuidV4(), 'Université Paris-Saclay']);

            $this->addSql('UPDATE school SET parent_school_id = (SELECT parent_school.id FROM parent_school limit 1)');
        }

        $this->addSql('ALTER TABLE school ALTER COLUMN parent_school_id SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN school.parent_school_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE school ADD CONSTRAINT FK_F99EDABB32616800 FOREIGN KEY (parent_school_id) REFERENCES parent_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F99EDABB32616800 ON school (parent_school_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE school DROP CONSTRAINT FK_F99EDABB32616800');
        $this->addSql('DROP TABLE parent_school');
        $this->addSql('DROP INDEX IDX_F99EDABB32616800');
        $this->addSql('ALTER TABLE school DROP parent_school_id');
    }
}
