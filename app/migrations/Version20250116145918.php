<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116145918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pastes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, content text, release_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expiration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', exposure VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E5468205D1B862B8 (hash), INDEX expiration_date_idx (expiration_date), INDEX exposure_idx (exposure), INDEX hash_idx (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pastes');
    }
}
