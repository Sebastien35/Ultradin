<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128131010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_address (id INT AUTO_INCREMENT NOT NULL, id_countryiso3 INT NOT NULL, id_user INT NOT NULL, city VARCHAR(255) NOT NULL, zip INT NOT NULL, INDEX IDX_5543718B239457E (id_countryiso3), INDEX IDX_5543718B6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B239457E FOREIGN KEY (id_countryiso3) REFERENCES country_iso3 (id_countryiso3)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B6B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B239457E');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B6B3CA4B');
        $this->addSql('DROP TABLE user_address');
    }
}
