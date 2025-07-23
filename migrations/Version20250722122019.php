<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722122019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cave_bouteille (id INT AUTO_INCREMENT NOT NULL, cave_id INT DEFAULT NULL, bouteille_id INT DEFAULT NULL, INDEX IDX_A7957BA77F05B85 (cave_id), INDEX IDX_A7957BA7F1966394 (bouteille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cave_bouteille ADD CONSTRAINT FK_A7957BA77F05B85 FOREIGN KEY (cave_id) REFERENCES cave (id)');
        $this->addSql('ALTER TABLE cave_bouteille ADD CONSTRAINT FK_A7957BA7F1966394 FOREIGN KEY (bouteille_id) REFERENCES bouteille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cave_bouteille DROP FOREIGN KEY FK_A7957BA77F05B85');
        $this->addSql('ALTER TABLE cave_bouteille DROP FOREIGN KEY FK_A7957BA7F1966394');
        $this->addSql('DROP TABLE cave_bouteille');
    }
}
