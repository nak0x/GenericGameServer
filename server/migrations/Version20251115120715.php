<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115120715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Custom up() to  fill  the slug with the sluggified name before constraint
        $this->addSql('
            CREATE TABLE __temp__game (
                id   INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name VARCHAR(128) NOT NULL,
                slug VARCHAR(255) NOT NULL
            )
        ');

        $this->addSql("
            INSERT INTO __temp__game (id, name, slug)
            SELECT
                id,
                name,
                LOWER(REPLACE(name, ' ', '-')) AS slug
            FROM game
        ");
        $this->addSql('DROP TABLE game');
        $this->addSql('ALTER TABLE __temp__game RENAME TO game');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, name FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL)');
        $this->addSql('INSERT INTO game (id, name) SELECT id, name FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
    }
}
