<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723125312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cave DROP INDEX IDX_57F6D41A76ED395, ADD UNIQUE INDEX UNIQ_57F6D41A76ED395 (user_id)');
        $this->addSql('ALTER TABLE cave DROP FOREIGN KEY FK_57F6D41A76ED395');
        $this->addSql('ALTER TABLE cave CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE cave ADD CONSTRAINT FK_57F6D41A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497F05B85');
        $this->addSql('DROP INDEX UNIQ_8D93D6497F05B85 ON user');
        $this->addSql('ALTER TABLE user DROP cave_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD cave_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497F05B85 FOREIGN KEY (cave_id) REFERENCES cave (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497F05B85 ON user (cave_id)');
        $this->addSql('ALTER TABLE cave DROP INDEX UNIQ_57F6D41A76ED395, ADD INDEX IDX_57F6D41A76ED395 (user_id)');
        $this->addSql('ALTER TABLE cave DROP FOREIGN KEY FK_57F6D41A76ED395');
        $this->addSql('ALTER TABLE cave CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cave ADD CONSTRAINT FK_57F6D41A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
