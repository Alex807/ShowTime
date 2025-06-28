<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628172026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX unique_edition_amenity ON edition_amenity (edition_id, amenity_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX not_overlapped_artist_performances ON edition_artist (edition_id, artist_id, performance_date, start_time, end_time)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket ADD valid_starting DATETIME DEFAULT NULL, ADD expires_at DATETIME DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX unique_edition_amenity ON edition_amenity
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX not_overlapped_artist_performances ON edition_artist
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket DROP valid_starting, DROP expires_at
        SQL);
    }
}
