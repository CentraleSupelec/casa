<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005151348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lessor_admin_user (id UUID NOT NULL, lessor_id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, last_login_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, verification_token VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A991BD05D737E9B1 ON lessor_admin_user (lessor_id)');
        $this->addSql('COMMENT ON COLUMN lessor_admin_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lessor_admin_user.lessor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE lessor_reset_password_request (id UUID NOT NULL, user_id UUID NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A4E83B22A76ED395 ON lessor_reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN lessor_reset_password_request.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lessor_reset_password_request.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lessor_reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN lessor_reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE lessor_admin_user ADD CONSTRAINT FK_A991BD05D737E9B1 FOREIGN KEY (lessor_id) REFERENCES lessor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lessor_reset_password_request ADD CONSTRAINT FK_A4E83B22A76ED395 FOREIGN KEY (user_id) REFERENCES lessor_admin_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lessor_reset_password_request DROP CONSTRAINT FK_A4E83B22A76ED395');
        $this->addSql('DROP TABLE lessor_admin_user');
        $this->addSql('DROP TABLE lessor_reset_password_request');
    }
}
