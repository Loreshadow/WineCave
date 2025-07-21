<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718100453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD username VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, DROP firstname, DROP lastname, CHANGE email email VARCHAR(180) NOT NULL, CHANGE role role VARCHAR(50) DEFAULT \'USER\' NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON user');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, DROP first_name, DROP last_name, DROP username, DROP roles, CHANGE email email VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(50) NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
