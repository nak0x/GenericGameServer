<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030093936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "action" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, endpoint_id INTEGER NOT NULL, method VARCHAR(255) NOT NULL, CONSTRAINT FK_47CC8C9221AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_47CC8C9221AF7E36 ON "action" (endpoint_id)');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, name VARCHAR(128) NOT NULL, provider VARCHAR(255) NOT NULL, CONSTRAINT FK_C7440455E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C7440455E48FD905 ON client (game_id)');
        $this->addSql('CREATE TABLE data (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ressource_id INTEGER NOT NULL, content CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_ADF3F363FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_ADF3F363FC6CD52A ON data (ressource_id)');
        $this->addSql('CREATE TABLE data_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL, content_type VARCHAR(255) NOT NULL, form_type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE data_type_ressource (data_type_id INTEGER NOT NULL, ressource_id INTEGER NOT NULL, PRIMARY KEY(data_type_id, ressource_id), CONSTRAINT FK_FC138F96A147DA62 FOREIGN KEY (data_type_id) REFERENCES data_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FC138F96FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FC138F96A147DA62 ON data_type_ressource (data_type_id)');
        $this->addSql('CREATE INDEX IDX_FC138F96FC6CD52A ON data_type_ressource (ressource_id)');
        $this->addSql('CREATE TABLE endpoint (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, template_id INTEGER DEFAULT NULL, name VARCHAR(128) NOT NULL, type VARCHAR(255) NOT NULL, route VARCHAR(255) NOT NULL, CONSTRAINT FK_C4420F7B5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4420F7B5DA0FB8 ON endpoint (template_id)');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL)');
        $this->addSql('CREATE TABLE game_user (game_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(game_id, user_id), CONSTRAINT FK_6686BA65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6686BA65A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6686BA65E48FD905 ON game_user (game_id)');
        $this->addSql('CREATE INDEX IDX_6686BA65A76ED395 ON game_user (user_id)');
        $this->addSql('CREATE TABLE ressource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, name VARCHAR(128) NOT NULL, CONSTRAINT FK_939F4544E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_939F4544E48FD905 ON ressource (game_id)');
        $this->addSql('CREATE TABLE ressource_endpoint (ressource_id INTEGER NOT NULL, endpoint_id INTEGER NOT NULL, PRIMARY KEY(ressource_id, endpoint_id), CONSTRAINT FK_8AF3B6A1FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8AF3B6A121AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8AF3B6A1FC6CD52A ON ressource_endpoint (ressource_id)');
        $this->addSql('CREATE INDEX IDX_8AF3B6A121AF7E36 ON ressource_endpoint (endpoint_id)');
        $this->addSql('CREATE TABLE template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL, template_name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "action"');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE data');
        $this->addSql('DROP TABLE data_type');
        $this->addSql('DROP TABLE data_type_ressource');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_user');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE ressource_endpoint');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
