<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122215629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id_category INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id_category)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id_product INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image_url VARCHAR(320) NOT NULL, stock INT NOT NULL, date_created DATETIME NOT NULL, technical_features LONGTEXT NOT NULL, availability TINYINT(1) NOT NULL, price DOUBLE PRECISION NOT NULL, date_updated DATETIME DEFAULT NULL, PRIMARY KEY(id_product)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id_user INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, phone VARCHAR(14) NOT NULL, default_payment_method INT DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
