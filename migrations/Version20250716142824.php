<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716142824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appellation (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_187A5B9898260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bouteille (id INT AUTO_INCREMENT NOT NULL, color_id INT DEFAULT NULL, region_id INT DEFAULT NULL, appellation_id INT DEFAULT NULL, producteur_id INT DEFAULT NULL, cave_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, ancien INT DEFAULT NULL, degres DOUBLE PRECISION DEFAULT NULL, quantite INT NOT NULL, purchase_price NUMERIC(10, 2) DEFAULT NULL, gout LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_11157C4C7ADA1FB5 (color_id), INDEX IDX_11157C4C98260155 (region_id), INDEX IDX_11157C4C7CDE30DD (appellation_id), INDEX IDX_11157C4CAB9BB300 (producteur_id), INDEX IDX_11157C4C7F05B85 (cave_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cave (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, visibility VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_57F6D41A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, bottle_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8933C432A76ED395 (user_id), INDEX IDX_8933C432DCF9352B (bottle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_stock (id INT AUTO_INCREMENT NOT NULL, bottles_id INT NOT NULL, user_id INT NOT NULL, modification INT NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5B68C134B11BD50 (bottles_id), INDEX IDX_5B68C13A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, bottle_id INT NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_CFBDFA14A76ED395 (user_id), INDEX IDX_CFBDFA14DCF9352B (bottle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producteur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F62F176F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, bottle_id INT NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_9CE12A31A76ED395 (user_id), INDEX IDX_9CE12A31DCF9352B (bottle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appellation ADD CONSTRAINT FK_187A5B9898260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4C7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4C98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4C7CDE30DD FOREIGN KEY (appellation_id) REFERENCES appellation (id)');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4CAB9BB300 FOREIGN KEY (producteur_id) REFERENCES producteur (id)');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4C7F05B85 FOREIGN KEY (cave_id) REFERENCES cave (id)');
        $this->addSql('ALTER TABLE cave ADD CONSTRAINT FK_57F6D41A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432DCF9352B FOREIGN KEY (bottle_id) REFERENCES bouteille (id)');
        $this->addSql('ALTER TABLE historique_stock ADD CONSTRAINT FK_5B68C134B11BD50 FOREIGN KEY (bottles_id) REFERENCES bouteille (id)');
        $this->addSql('ALTER TABLE historique_stock ADD CONSTRAINT FK_5B68C13A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14DCF9352B FOREIGN KEY (bottle_id) REFERENCES bouteille (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176F92F3E70 FOREIGN KEY (country_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31DCF9352B FOREIGN KEY (bottle_id) REFERENCES bouteille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appellation DROP FOREIGN KEY FK_187A5B9898260155');
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4C7ADA1FB5');
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4C98260155');
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4C7CDE30DD');
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4CAB9BB300');
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4C7F05B85');
        $this->addSql('ALTER TABLE cave DROP FOREIGN KEY FK_57F6D41A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432DCF9352B');
        $this->addSql('ALTER TABLE historique_stock DROP FOREIGN KEY FK_5B68C134B11BD50');
        $this->addSql('ALTER TABLE historique_stock DROP FOREIGN KEY FK_5B68C13A76ED395');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14DCF9352B');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176F92F3E70');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A31A76ED395');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A31DCF9352B');
        $this->addSql('DROP TABLE appellation');
        $this->addSql('DROP TABLE bouteille');
        $this->addSql('DROP TABLE cave');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE historique_stock');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
