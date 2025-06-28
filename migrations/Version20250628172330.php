<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628172330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity ADD edition_amenity_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity ADD CONSTRAINT FK_B0BAB31A51E5B828 FOREIGN KEY (edition_amenity_id) REFERENCES edition_amenity (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B0BAB31A51E5B828 ON purchase_amenity (edition_amenity_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity DROP FOREIGN KEY FK_B0BAB31A51E5B828
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B0BAB31A51E5B828 ON purchase_amenity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity DROP edition_amenity_id
        SQL);
    }
}
