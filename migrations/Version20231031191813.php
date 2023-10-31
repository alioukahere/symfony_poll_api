<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031191813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE `option` (
                id INT AUTO_INCREMENT NOT NULL,
                poll_id INT NOT NULL,
                content VARCHAR(255) NOT NULL,
                INDEX IDX_5A8600B03C947C0F (poll_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE poll (
                id INT AUTO_INCREMENT NOT NULL,
                owner_id INT NOT NULL,
                question VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_84BCFA457E3C61F9 (owner_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B03C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id)'
        );
        $this->addSql(
            'ALTER TABLE poll ADD CONSTRAINT FK_84BCFA457E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B03C947C0F');
        $this->addSql('ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA457E3C61F9');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE poll');
    }
}
