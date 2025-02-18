<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218082147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_verifications (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', verified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', verified TINYINT(1) NOT NULL, code_verification VARCHAR(512) NOT NULL, type_verification VARCHAR(255) NOT NULL, INDEX IDX_8563EF4BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_verifications ADD CONSTRAINT FK_8563EF4BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_verifications DROP FOREIGN KEY FK_8563EF4BA76ED395');
        $this->addSql('DROP TABLE users_verifications');
    }
}
