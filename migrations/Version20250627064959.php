<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627064959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD edition_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD CONSTRAINT FK_9ADA35DE74281A5E FOREIGN KEY (edition_id) REFERENCES festival_edition (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9ADA35DE74281A5E ON edition_review (edition_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP FOREIGN KEY FK_9ADA35DE74281A5E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9ADA35DE74281A5E ON edition_review
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP edition_id
        SQL);
    }
}
