<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221121094718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guarantor ADD sort_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lease_type ADD sort_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE occupation_mode ADD sort_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stay_duration ADD sort_order INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stay_duration DROP sort_order');
        $this->addSql('ALTER TABLE occupation_mode DROP sort_order');
        $this->addSql('ALTER TABLE guarantor DROP sort_order');
        $this->addSql('ALTER TABLE lease_type DROP sort_order');
    }
}
