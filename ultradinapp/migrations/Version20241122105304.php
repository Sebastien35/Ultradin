<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122105304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE user MODIFY id_user INT NOT NULL');
        $this->addSql('DROP INDEX IDX_65964723c91566b00580a6cf22 ON user');
        $this->addSql('DROP INDEX `primary` ON user');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD default_payment_method INT NOT NULL, DROP role, DROP default_payment, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE id_user id INT AUTO_INCREMENT NOT NULL, CHANGE date_created created_at DATETIME NOT NULL, CHANGE cellphone phone VARCHAR(14) NOT NULL');
        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user RENAME INDEX idx_e12875dfb3b1d92d7d7c5377e2 TO UNIQ_IDENTIFIER_EMAIL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id_product INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image_url TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, stock VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date_created DATETIME NOT NULL, caracteristiques_techniques TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, availability VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, price NUMERIC(15, 2) NOT NULL, date_updated DATETIME NOT NULL, UNIQUE INDEX IDX_22cc43e9a74d7498546e9a63e7 (name), PRIMARY KEY(id_product)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE `user` MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON `user`');
        $this->addSql('ALTER TABLE `user` ADD role VARCHAR(50) NOT NULL, ADD default_payment VARCHAR(50) NOT NULL, DROP roles, DROP default_payment_method, CHANGE email email VARCHAR(320) NOT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE id id_user INT AUTO_INCREMENT NOT NULL, CHANGE created_at date_created DATETIME NOT NULL, CHANGE phone cellphone VARCHAR(14) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX IDX_65964723c91566b00580a6cf22 ON `user` (cellphone)');
        $this->addSql('ALTER TABLE `user` ADD PRIMARY KEY (id_user)');
        $this->addSql('ALTER TABLE `user` RENAME INDEX uniq_identifier_email TO IDX_e12875dfb3b1d92d7d7c5377e2');
    }
}
