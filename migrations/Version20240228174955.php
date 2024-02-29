<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228174955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE obras_completas (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(100) NOT NULL, autor VARCHAR(255) NOT NULL, titulo VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, vol VARCHAR(255) DEFAULT NULL, clasificacion VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D118C681989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revistas (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(100) NOT NULL, nombre VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, editorial VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_841864B8989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sitios (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(100) NOT NULL, nombre VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_20645B7A989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE obras_completas');
        $this->addSql('DROP TABLE revistas');
        $this->addSql('DROP TABLE sitios');
        $this->addSql('DROP TABLE user');
    }
}
