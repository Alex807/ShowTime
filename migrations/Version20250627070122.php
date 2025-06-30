<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627070122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity ADD edition_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity ADD CONSTRAINT FK_B8EF788174281A5E FOREIGN KEY (edition_id) REFERENCES festival_edition (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B8EF788174281A5E ON edition_amenity (edition_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity DROP FOREIGN KEY FK_B8EF788174281A5E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B8EF788174281A5E ON edition_amenity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity DROP edition_id
        SQL);
    }
}
