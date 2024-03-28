<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213114014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds ROLE_SUPER_ADMIN Role to Database. In system must be at least one user with ROLE_SUPER_ADMIN'
            . 'Default credentials for that user are in .env file under SUPER_ADMIN_EMAIL and SUPER_ADMIN_PASSWORD keys. ';

    }

    public function up(Schema $schema): void
    {
        $email = '\'' . $_ENV['SUPER_ADMIN_EMAIL'] . '\'';
        $password = '\'' . $_ENV['SUPER_ADMIN_PASSWORD'] . '\'';
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO role (id, name, display_name) VALUES (1, \'ROLE_SUPER_ADMIN\', \'Super Admin\')');
        $this->addSql('INSERT INTO "user" (id, email, password, createdat, updatedat) VALUES (1, '. $email .', '. $password .', NOW(), NOW())');
        $this->addSql('INSERT INTO role_user (role_id, user_id) VALUES (1, 1)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM role_user WHERE role_id = 1 AND user_id = 1');
        $this->addSql('DELETE FROM "user" WHERE id = 1');
        $this->addSql('DELETE FROM role WHERE id = 1');
    }
}
