<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627065614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD edition_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD CONSTRAINT FK_E2C0229F74281A5E FOREIGN KEY (edition_id) REFERENCES festival_edition (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2C0229F74281A5E ON edition_artist (edition_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP FOREIGN KEY FK_E2C0229F74281A5E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E2C0229F74281A5E ON edition_artist
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP edition_id
        SQL);
    }
}
